<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CronJobTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('Cron Job Test')
                    ->view('emails.cron-test');
    }
}
