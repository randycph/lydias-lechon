<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\EcommerceModel\SalesHeader;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCancelledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(SalesHeader $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Order Cancelled Due to Unpaid Status')
                    ->markdown('emails.order_cancelled');
    }
}
