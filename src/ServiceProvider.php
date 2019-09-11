<?php

namespace FilippoToso\LaravelHelpers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

        Collection::macro('pagination', function ($perPage = 15, $page = null, $options = []) {
            $page = $page ? : (Paginator::resolveCurrentPage() ? : 1);
            return new LengthAwarePaginator($this->forPage($page, $perPage), $this->count(), $perPage, $page, $options);
        });

        $this->registerResponseMarco();

        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'laravel-helpers');

        $this->publishes([
            dirname(__DIR__) . '/resources/views' => resource_path('views/vendor/filippo-toso/laravel-helpers'),
        ], 'views');

    }

    protected function registerResponseMarco()
    {

        Response::macro('error', function ($code, $message, $errors = [], $status = 400) {
            return Response::make([
                'error' => [
                    'code' => $code,
                    'message' => $message,
                    'errors' => $errors,
                ],
            ], $status);
        });

        Response::macro('unauthenticated', function () {
            return Response::make([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthenticated',
                    'errors' => [],
                ],
            ], 401);
        });

        Response::macro('notFound', function () {
            return Response::make([
                'error' => [
                    'code' => 404,
                    'message' => 'Resource not found',
                    'errors' => [],
                ],
            ], 404);
        });

        Response::macro('success', function ($status = 200) {
            return Response::make([
                'data' => [
                    'status' => 'success',
                ],
            ], $status);
        });

        Response::macro('created', function ($id) {
            return Response::make([
                'data' => [
                    'id' => $id,
                ],
            ], 201);
        });

        Response::macro('updated', function () {
            return Response::make('', 204);
        });

        Response::macro('nothing', function () {
            return Response::make('', 204);
        });

        Response::macro('deleted', function () {
            return Response::make('', 204);
        });

        Response::macro('forbidden', function ($message, $errors = []) {
            return Response::make([
                'error' => [
                    'code' => 403,
                    'message' => $message,
                    'errors' => $errors,
                ],
            ], 403);
        });

        Response::macro('validation', function ($errors = []) {
            return Response::make([
                'error' => [
                    'code' => 100,
                    'message' => 'Failed validation!',
                    'errors' => $errors,
                ],
            ], 422);
        });

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
