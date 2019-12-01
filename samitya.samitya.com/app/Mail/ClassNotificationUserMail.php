<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClassNotificationUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *  
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.classNotificationUserMail')
            ->subject('Reminder For Live Class on '.env('APP_NAME'))
            ->with('content',$this->content);
    }
}
