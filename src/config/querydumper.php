<?php

return [

    /*
    | Enable or disable query dumper. By default it is disabled.
    */

    'enabled' => env('QUERYDUMPER', false),

    /*
    | Whatever value for this config is set, you will be able to see all running quries by appending
    | this value in your url as query string.
    |
    | Example: http://www.yourapp.com/someurl?qqq
    */

    'querystring_name' => 'qqq',

    /*
    | If true, it will also format shown SQL queries.
    */

    'format_sql' => true,

    /*
    | If true, it will dump queries on current page. If affects your layout,
    | you can set this to false and be able to view dumped queries in new page.
    */

    'same_page' => false,
];
