<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeliveryAssignedMultipleMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $delivery;
    public $driver;
    public $addresses;

    /**
     * Create a new message instance.
     */
    public function __construct($delivery, $driver, $addresses = null)
    {
        $this->delivery = $delivery;
        $this->driver = $driver;
        $this->addresses = $addresses;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Delivery Assigned')
                    ->markdown('emails.delivery-assigned-multiple', [
                        'delivery' => $this->delivery,
                        'driver' => $this->driver,
                        'addresses' => $this->addresses,
                    ]);
    }
}
