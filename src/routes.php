<?php

Route::group(
    [
        'namespace' => 'Sarfraznawaz2005\QueryDumper',
        'prefix' => 'querydumper'
    ],
    function () {
        Route::get('dump', function () {
            return file_get_contents(__DIR__ . '/tmp/log.html');
        });
    }
);

