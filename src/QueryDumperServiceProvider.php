<?php

namespace Sarfraznawaz2005\QueryDumper;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Sarfraznawaz2005\QueryDumper\Libs\SqlFormatter;

class QueryDumperServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        // publish our files over to main laravel app
        $this->publishes([
            __DIR__ . '/config/querydumper.php' => config_path('querydumper.php')
        ]);

        if (!$this->isEnabled()) {
            return;
        }

        DB::listen(function ($sql, $bindings = null, $time = null) {
            if ($sql instanceof QueryExecuted) {
                $formatMethod = config('querydumper.format_sql') ? 'format' : 'highlight';

                $time = $sql->time;

                $query = $this->applyBindings($sql->sql, $sql->bindings);
                $queryParts = explode(' ', $sql->sql);

                if (strtolower($queryParts[0]) === 'select') {
                    $result = DB::select(DB::raw('EXPLAIN ' . $query));

                    if (isset($result[0])) {
                        $table = 'Time: <strong>' . $time . 'ms</strong><br>' . SqlFormatter::$formatMethod($query);
                        $table .= $this->table([(array)$result[0]]);

                        echo '<div style="background: #F7F0CB; margin: 0 20px 0 20px; overflow:auto; color:#000; padding: 5px; width:auto;">' . $table . '</div>';
                    }
                } else {
                    if (strtolower($queryParts[0]) !== 'explain') {
                        echo '<div style="background: #F7F0CB; margin: 0 20px 0 20px; overflow:auto; color:#000; padding: 5px; width:auto;"><strong>' . SqlFormatter::$formatMethod($query) . '</strong></div>';
                    }
                }
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function isEnabled()
    {
        $enabled = config('querydumper.enabled');

        if (!$enabled) {
            return false;
        }

        $queryString = config('querydumper.querystring_name');

        if ($this->app->runningInConsole()) {
            return in_array($queryString, $_SERVER['argv']);
        }

        return request()->exists($queryString);
    }

    private function applyBindings($sql, array $bindings)
    {
        if (empty($bindings)) {
            return $sql;
        }

        $placeholder = preg_quote('?', '/');

        foreach ($bindings as $binding) {
            $binding = is_numeric($binding) ? $binding : "'{$binding}'";
            $sql = preg_replace('/' . $placeholder . '/', $binding, $sql, 1);
        }

        return $sql;
    }

    protected function table($data)
    {
        $keys = array_keys(end($data));
        $size = array_map('strlen', $keys);

        foreach (array_map('array_values', $data) as $e) {
            $size = array_map('max', $size,
                array_map('strlen', $e));
        }

        foreach ($size as $n) {
            $form[] = "%-{$n}s";
            $line[] = str_repeat('-', $n);
        }

        $form = '| ' . implode(' | ', $form) . " |\n";
        $line = '+-' . implode('-+-', $line) . "-+\n";
        $rows = array(vsprintf($form, $keys));

        foreach ($data as $e) {
            $rows[] = vsprintf($form, $e);
        }

        echo "<pre style='margin: 50px 20px 0 20px; background: #F7F0CB; font-weight:bold; border-radius: 0; border:0; font-size:12px;'>\n";
        echo $line . implode($line, $rows) . $line;
        echo "</pre>\n";
    }
}