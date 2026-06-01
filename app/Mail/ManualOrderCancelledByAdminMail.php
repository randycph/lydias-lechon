<?php

namespace App\Mail;

use App\EcommerceModel\SalesHeader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ManualOrderCancelledByAdminMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(SalesHeader $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Order Cancelled')
                    ->markdown('emails.manual_order_cancelled-by-admin');
    }
}
