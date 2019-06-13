<?php

namespace FilippoToso\LaravelHelpers\Facades;

use Illuminate\Support\Facades\Facade;
use FilippoToso\LaravelHelpers\UI\Gravatar as BaseClass;

class Gravatar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BaseClass::class;
    }
}