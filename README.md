**Abandoned:**

[Use this package instead](https://github.com/sarfraznawaz2005/indexer)

---


# Laravel QueryDumper

[![laravel 5.1](https://img.shields.io/badge/Laravel-5.1-brightgreen.svg?style=flat-square)](http://laravel.com)
[![laravel 5.2](https://img.shields.io/badge/Laravel-5.2-brightgreen.svg?style=flat-square)](http://laravel.com)
[![laravel 5.3](https://img.shields.io/badge/Laravel-5.3-brightgreen.svg?style=flat-square)](http://laravel.com)
[![downloads](https://poser.pugx.org/sarfraznawaz2005/querydumper/downloads)](https://packagist.org/packages/sarfraznawaz2005/querydumper)

## Introduction ##

Simple Laravel 5 package to dump all running queries on the page. If it's `SELECT` query, it will also show `EXPLAIN` information against it.

## Screenshot ##

![Main Window](https://raw.github.com/sarfraznawaz2005/querydumper/master/screen.png)

## Requirements ##

 - PHP >= 5.6
 - Laravel 5 (tested on Laravel 5.1, 5.2,  5.3 and 5.4)

## Installation ##

Install via composer
```
composer require sarfraznawaz2005/querydumper
```

Add Service Provider to `config/app.php` in `providers` section
```php
Sarfraznawaz2005\QueryDumper\QueryDumperServiceProvider::class,
```

Run `php artisan vendor:publish` to publish package's config file. You should now have `querydumper.php` file published in `app/config` folder.

## Config Options ##

 - `enabled` : Enable or disable QueryDumper. By default it is disabled. If you are on local environment, you can also just add `QUERYDUMPER=true` to env file to enable it.
 - `querystring_name` : Whatever value for this config is set, you will be able to see all running quries by appending this value in your url as query string. Example: `http://www.yourapp.com/someurl?qqq`. Default value is `qqq`.
 - `format_sql` : If true, it will also format shown SQL queries. Default `false`.
 - `same_page` : If true, it will dump queries on current page you are on. If it affects your layout, you can set this to `false` and be able to view dumped queries on a page available at url `http://yoursite.com/querydumper/dump`. In this case, first visit the page you want to see queries of by appending `?querystring_name` value there and then visit `querydumper/dump` route to see quries for your last visited page. Default `true`.

## Related Package ##

[QueryLine](https://github.com/sarfraznawaz2005/queryline)

## License ##

This code is published under the [MIT License](http://opensource.org/licenses/MIT).
This means you can do almost anything with it, as long as the copyright notice and the accompanying license file is left intact.
