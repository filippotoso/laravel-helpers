<?php

namespace FilippoToso\LaravelHelpers\Middlewares;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use FilippoToso\URI\URI;

class AppendBackendParams
{
    protected const SESSION_PREFIX = '___backend-params___';

    // Params to be remembered
    protected $params = ['search', 'page'];

    // Session var name prefix
    protected $sessionPrefix = self::SESSION_PREFIX;

    // When to save the params
    protected $indexPostfix = 'index';

    // When to use the saved parameters
    protected $actionPostfixes = ['store', 'update', 'delete'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $routeName = $request->route()->getName();
        $sessionName = $this->sessionPrefix . '.' . Str::beforeLast($routeName, '.');

        if (Str::endsWith($routeName, '.' . $this->indexPostfix)) {
            $request->session()->put($this->sessionPrefix, []); // Remove other parameters for clean navigation
            $request->session()->put($sessionName, $request->only($this->params));
        }

        $response = $next($request);

        if (is_a($response, RedirectResponse::class) && in_array(Str::afterLast($routeName, '.'), $this->actionPostfixes)) {

            $params = $request->session()->get($sessionName, []);

            $url = URI::make($response->getTargetUrl());

            foreach ($params as $name => $value) {
                $url->set($name, $value);
            }

            $response->setTargetUrl($url->url());
        }

        return $response;
    }
}
