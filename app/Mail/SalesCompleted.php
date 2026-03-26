<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalesCompleted extends Mailable implements ShouldQueue
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
        if ($this->h?->is_multiple_address == 1) {
            return $this->markdown('mail.sales-completed-mutiple')
                ->subject('Sales Transaction');
        } else {
            return $this->view('mail.sales-completed')
                ->subject('Sales Transaction');
        }
    }
}
