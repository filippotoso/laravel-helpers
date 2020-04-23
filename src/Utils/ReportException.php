<?php

namespace FilippoToso\LaravelHelpers\Utils;

use FilippoToso\LaravelHelpers\Mails\ExceptionReported;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Exception;
use Throwable;

class ReportException
{
    public static function run(Throwable $exception)
    {
        $exclude = config('mail_exceptions.exclude');
        if (!in_array($exception, $exclude)) {
            $key = 'filippotoso.laravelhelpers.mailexceptions.' . get_class($exception);
            if (!Cache::has($key)) {
                // Cache::put($key, true, config('mail_exceptions.throttle'));

                if (!$exception instanceof Exception) {
                    $exception = new FatalThrowableError($exception);
                }

                try {

                    $request = request();

                    $html = View::make('laravel-helpers::mails.exception', [
                        'message' => $exception->getMessage(),
                        'method' => $request->method(),
                        'url' =>  $request->fullUrl(),
                        'content' => $request->getContent(),
                    ])->render();

                    $flatException = FlattenException::create($exception);
                    $handler = new SymfonyExceptionHandler();
                    $exceptionHtml = $handler->getHtml($flatException);
                    $html = preg_replace('#(<body[^>]*>)#si', '$1' . $html, $exceptionHtml);
                } catch (Exception $e) {
                    $html = (string) $e;
                }

                Mail::queue(new ExceptionReported($html));
            }
        }
    }
}
