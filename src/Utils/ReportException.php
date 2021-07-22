<?php

namespace FilippoToso\LaravelHelpers\Utils;

use FilippoToso\LaravelHelpers\Mails\ExceptionReported;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Exception;
use Throwable;

class ReportException
{
    public static function run(Throwable $exception)
    {
        $exclude = config('mail_exceptions.exclude');

        $exceptionClass = get_class($exception);

        if (!in_array($exceptionClass, $exclude)) {
            $key = 'filippotoso.laravelhelpers.mailexceptions.' . get_class($exception);
            if (!Cache::has($key)) {
                Cache::put($key, true, config('mail_exceptions.throttle'));

                try {
                    $flatException = ($exception instanceof Exception) ? FlattenException::create($exception) : FlattenException::createFromThrowable($exception);
                    $handler = new HtmlErrorRenderer(true);
                    $exceptionHtml = $handler->getBody($flatException);

                    $request = request();

                    $payload = [
                        'message' => $exception->getMessage(),
                        'method' => $request->method(),
                        'url' =>  $request->fullUrl(),
                        'content' => $request->getContent(),
                        'headers' => $request->headers->all(),
                        'html' => $exceptionHtml,
                    ];

                    if (view()->exists('laravel-helpers::mails.exception')) {
                        $html = View::make('laravel-helpers::mails.exception', $payload)->render();
                    } else {
                        $html = <<<HTML
<h1>{$payload['message']}</h1>
<p>
    <b>Method:</b> {$payload['method']}<br>
    <b>Url:</b> {$payload['url']}<br>
    <b>Content:</b> {$payload['content']}<br>
    <br>
    {$payload['html']}
</p>                      
HTML;
                    }
                } catch (Exception $e) {
                    $html = (string) $e;
                }

                Mail::queue(new ExceptionReported($html));
            }
        }
    }
}
