<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentSubmittedAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $h;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment)
    {
        $this->h = $payment;
     
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.payment.payment-submitted-admin')
            ->subject('New Payment Submitted ('.$this->h->order_number.')');
    }
}
