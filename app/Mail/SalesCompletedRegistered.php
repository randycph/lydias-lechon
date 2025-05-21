<?php

namespace App\Mail;

use App\EcommerceModel\SalesHeader;
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
        // $h = SalesHeader::find($this->sh->id);

        return $this->view('mail.sales-completed-registered', ['h' => $this->h])
            ->subject('Sales Transaction');
    }
}
