<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentDoneMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content,$invoice)
    {
        $this->content = $content;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
    {
        return $this->markdown('emails.offlinePaymentDoneMail')
            ->subject('Payment')
            ->with('content',$this->content)
            ->attach($this->invoice,[
                    'as' => 'invoice.pdf',
                    'mime' => 'application/pdf',
                ]);
    }
}
