<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CareerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $applicant;
    public $resume;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $applicant
     */
    public function __construct($setting, $applicant, $resume)
    {
        $this->setting = $setting;
        $this->applicant = $applicant;
        $this->resume = $resume;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (empty($this->resume)) {
            return $this->view('mail.careers')
                ->subject('Application: '.$this->applicant['name']);
        } else {
            return $this->view('mail.careers')
                ->subject('Application: '.$this->applicant['name'])
                ->attach($this->resume, [
                    'as' => $this->applicant['name'].' - resume.pdf',
                    'mime' => 'application/pdf',
                ]);
        }
    }
}
