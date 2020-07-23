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

                    $request = request();

                    $html = View::make('laravel-helpers::mails.exception', [
                        'message' => $exception->getMessage(),
                        'method' => $request->method(),
                        'url' =>  $request->fullUrl(),
                        'content' => $request->getContent(),
                    ])->render();

                    $flatException = ($exception instanceof Exception) ? FlattenException::create($exception) : FlattenException::createFromThrowable($exception);
                    $handler = new HtmlErrorRenderer();
                    $exceptionHtml = $handler->getBody($flatException);
                    $html = preg_replace('#(<body[^>]*>)#si', '$1' . $html, $exceptionHtml);
                } catch (Exception $e) {
                    $html = (string) $e;
                }

                Mail::queue(new ExceptionReported($html));
            }
        }
    }
}
