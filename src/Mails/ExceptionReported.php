<?php

namespace FilippoToso\LaravelHelpers\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionReported extends Mailable
{
    use Queueable, SerializesModels;

    public $html;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($html)
    {
        $this->html = $html;
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
            ->html($this->html);
    }
}
