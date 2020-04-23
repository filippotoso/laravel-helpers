<?php

namespace FilippoToso\LaravelHelpers\Utils;

use FilippoToso\LaravelHelpers\Mails\ExceptionReported;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Exception;

class ReportException
{
    public static function run(Exception $exception)
    {
        $exclude = config('mail_exceptions.exclude');
        if (!in_array($exception, $exclude)) {
            $key = 'filippotoso.laravelhelpers.mailexceptions.' . get_class($exception);
            if (!Cache::has($key)) {
                Mail::queue(new ExceptionReported($exception));
                Cache::put($key, true, config('mail_exceptions.throttle'));
            }
        }
    }
}
