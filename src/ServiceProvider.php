<?php

namespace FilippoToso\LaravelHelpers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Collection;

class ServiceProvider extends EventServiceProvider
{

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

        parent::boot();

        // @set('variable', $value)
        Blade::directive('set', function ($expression) {
            return preg_replace("#\s*['\"](.*?)['\"]\s*\,(.*)#s", '<' . '?php $$1 =$2; ?' . '>', $expression);
        });

        Blade::directive('unset', function ($expression) {
            return '<' . "?php unset($expression); ?" . '>';
        });

        Blade::if('hide', function ($condition = true) {
            return !$condition;
        });

        Validator::extend('not_exists', function ($attribute, $value, $parameters) {
            return DB::table($parameters[0])
                ->where($parameters[1], '=', $value)
                ->count() < 1;
        });

        Collection::macro('containsObject', function ($property, $value, $strict = false) {
            return $this->contains(function ($item) use ($property, $value, $strict) {
                return ($strict) ? $item->$property === $value : $item->$property == $value;
            });
        });

        Collection::macro('uniqueObject', function ($property = 'id') {
            return $this->unique(function ($item) use ($property) {
                return $item->$property;
            });
        });

        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'laravel-helpers');

        $this->publishes([
            dirname(__DIR__) . '/resources/views' => resource_path('views/vendor/filippo-toso/laravel-helpers'),
        ], 'views');

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

    }

}
