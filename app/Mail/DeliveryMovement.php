<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeliveryMovement extends Mailable
{
    use Queueable, SerializesModels;
    public $h;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($delivery)
    {
        $this->h = $delivery;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $orderNumber = $this->h->order_number;
        $subject = "Your Order From Lydia's Lechon (Order No. $orderNumber)";
        return $this->view('mail.delivery.status-change')
            ->subject($subject);
    }
}
