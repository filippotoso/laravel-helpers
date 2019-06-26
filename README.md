# Controllers Generator

A couple of helpers for Laravel development

## Requirements

- PHP 7.1.3+
- Laravel 5.5+

## Installing

Use Composer to install it:

```
composer require filippo-toso/laravel-helpers
```

## What does it do?

This package includes:

- @set Blade directive to ser variables withing a Blade view
- field() helper and Field class to handle form inputs
- Breadcrumbs class, facade and views to display breadcrumbs

  Some traits to help with development.

More to come! 

## Resources

You can publish the views with the following command:

```
php artisan vendor:publish --tag=views --provider="FilippoToso\LaravelHelpers\ServiceProvider"
```
