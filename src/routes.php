<?php

Route::group(
    [
        'namespace' => 'Sarfraznawaz2005\QueryDumper',
        'prefix' => 'querydumper'
    ],
    function () {
        $samePage = config('querydumper.same_page');

        if ($samePage) {
            abort(404);
        }

        Route::get('dump', function () {
            return file_get_contents(__DIR__ . '/tmp/log.html');
        });
    }
);

