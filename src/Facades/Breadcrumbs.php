<?php

namespace FilippoToso\LaravelHelpers\Facades;

use Illuminate\Support\Facades\Facade;
use FilippoToso\LaravelHelpers\UI\Breadcrumbs as BaseClass;

class Breadcrumbs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BaseClass::class;
    }
}