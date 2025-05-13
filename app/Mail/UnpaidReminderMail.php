<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\EcommerceModel\SalesHeader;

class UnpaidReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(SalesHeader $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Reminder: Your Order is Still Unpaid')
                    ->markdown('emails.unpaid_reminder');
    }
}
