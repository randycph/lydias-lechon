<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CartReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $cartItems;

    /**
     * Create a new message instance.
     */
    public function __construct($cartItems)
    {
        $this->cartItems = $cartItems;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cart Reminder Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Reminder: You have items in your cart')
            ->markdown('emails.cart.reminder');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
