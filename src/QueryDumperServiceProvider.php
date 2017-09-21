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

    private static $counter;

    public function boot()
    {
        // routes
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        // publish our files over to main laravel app
        $this->publishes([
            __DIR__ . '/config/querydumper.php' => config_path('querydumper.php')
        ]);

        if (!$this->isEnabled()) {
            return;
        }

        self::$counter = 1;

        if (!is_dir(__DIR__ . '/tmp')) {
            mkdir(__DIR__ . '/tmp');
        } else {
            @unlink(__DIR__ . '/tmp/log.html');
        }

        DB::listen(function ($sql, $bindings = null, $time = null) {
            if ($sql instanceof QueryExecuted) {
                $time = $sql->time;
                $bindings = $sql->bindings;
                $sql = $sql->sql;
            }

            $this->output($sql, $bindings, $time);
        });
    }

    protected function output($sql, $bindings, $time)
    {
        self::$counter++;

        $currentQuery = '';
        $samePage = config('querydumper.same_page');
        $formatMethod = config('querydumper.format_sql') ? 'format' : 'highlight';

        $query = $this->applyBindings($sql, $bindings);
        $queryParts = explode(' ', $sql);

        if (gettype($query) != 'string') {
            $query = $query->getValue();
            $queryParts = explode(' ', $sql);
        }

        if (strtolower($queryParts[0]) === 'select') {
            $currentQuery = '<strong>#' . (--self::$counter) . ' - ' . $time . 'ms</strong><br>' . SqlFormatter::$formatMethod($query);

            $result = DB::select(DB::raw('EXPLAIN ' . $query));

            if (isset($result[0])) {
                $currentQuery .= $this->table([(array)$result[0]]);
            }
        } else {
            if (strtolower($queryParts[0]) !== 'explain') {
                $currentQuery = SqlFormatter::$formatMethod($query);
            }
        }

        $currentQuery = '<div style="background: #F7F0CB; margin: 0 20px 0 20px; overflow:auto; color:#000; padding: 5px; width:auto;">' . $currentQuery . '</div>';

        if (!$samePage) {
            file_put_contents(__DIR__ . '/tmp/log.html', $currentQuery, FILE_APPEND);
        } else {
            echo $currentQuery;
        }
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

    protected function isEnabled()
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

    protected function applyBindings($sql, array $bindings)
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

        return "<pre>\n" . $line . implode($line, $rows) . $line . "</pre>\n";
    }
}