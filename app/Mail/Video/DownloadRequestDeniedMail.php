<?php

namespace App\Mail\Video;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DownloadRequestDeniedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $request;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $request
     */
    public function __construct($setting, $request)
    {
        $this->setting = $setting;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.video.download-request-denied')
            ->text('mail.video.download-request-denied_plain')
            ->subject('Denied Video Link Request');
    }
}
