<?php

namespace FilippoToso\LaravelHelpers\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Exception;

class ExceptionReported extends Mailable
{
    use Queueable, SerializesModels;

    public $exception;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail_exceptions.from.address'), config('mail_exceptions.from.name'))
            ->to(config('mail_exceptions.to.address'), config('mail_exceptions.to.name'))
            ->subject(config('mail_exceptions.subject'))
            ->html($this->buildHtml());
    }

    protected function buildHtml()
    {
        try {
            $flatException = FlattenException::create($this->exception);

            $handler = new SymfonyExceptionHandler();

            $html = $handler->getHtml($flatException);

            $request = request();

            return View::make('laravel-helpers::mails.exception', [
                'message' => $this->exception->getMessage(),
                'method' => $request->method(),
                'url' =>  $request->fullUrl(),
                'content' => $request->getContent(),
                'exception' => $html,
            ])->render();
        } catch (Exception $e) {
            return (string) $e;
        }
    }
}
