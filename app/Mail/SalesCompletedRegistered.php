<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalesCompletedRegistered extends Mailable
{
    use Queueable, SerializesModels;
    public $h;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sales)
    {
        $this->h = $sales;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->h?->deliveryAddress && count($this->h->deliveryAddress) > 0) {
            return $this->markdown('mail.sales-completed-mutiple')
                ->subject('Sales Transaction');
        } else {
            return $this->view('mail.sales-completed-registered', ['h' => $this->h])
                ->subject('Sales Transaction');
        }
    }
}
