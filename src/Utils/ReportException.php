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

                    $html = View::make('filippo-toso-laravel-helpers::mails.exception', [
                        'message' => $exception->getMessage(),
                        'method' => $request->method(),
                        'url' =>  $request->fullUrl(),
                        'content' => $request->getContent(),
                        'headers' => $request->headers->all(),
                        'html' => $exceptionHtml,
                    ])->render();
                } catch (Exception $e) {
                    $html = (string) $e;
                }

                Mail::queue(new ExceptionReported($html));
            }
        }
    }
}
