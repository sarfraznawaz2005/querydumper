# Laravel QueryDumper

<a href="https://packagist.org/packages/sarfraznawaz2005/querydumper"><img src="https://poser.pugx.org/sarfraznawaz2005/querydumper/version" alt="Stable Build" /></a>
<a href="https://codeclimate.com/github/sarfraznawaz2005/querydumper"><img src="https://codeclimate.com/github/sarfraznawaz2005/querydumper/badges/gpa.svg" /></a>
[![Laravel 5.1](https://img.shields.io/badge/Laravel-5.1-brightgreen.svg?style=flat-square)](http://laravel.com)
[![Laravel 5.2](https://img.shields.io/badge/Laravel-5.2-brightgreen.svg?style=flat-square)](http://laravel.com)
[![Laravel 5.3](https://img.shields.io/badge/Laravel-5.3-brightgreen.svg?style=flat-square)](http://laravel.com)
[![Laravel 5.4](https://img.shields.io/badge/Laravel-5.4-brightgreen.svg?style=flat-square)](http://laravel.com)
[![Total Downloads](https://poser.pugx.org/sarfraznawaz2005/querydumper/downloads)](https://packagist.org/packages/sarfraznawaz2005/querydumper)

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

 - `enabled` : Enable or disable query dumper. By default it is disabled. If you are on local environment, you can also just add `QUERYDUMPER=true` to env file to enable it.
 - `querystring_name` : Whatever value for this config is set, you will be able to see all running quries by appending this value in your url as query string. Example: `http://www.yourapp.com/someurl?qqq`

## License ##

This code is published under the [MIT License](http://opensource.org/licenses/MIT).
This means you can do almost anything with it, as long as the copyright notice and the accompanying license file is left intact.
