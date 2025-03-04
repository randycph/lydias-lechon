<?php

namespace App\Mail\Video;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DownloadRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $request;
    public $link;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $request
     * @param $link
     */
    public function __construct($setting, $request, $link)
    {
        $this->setting = $setting;
        $this->request = $request;
        $this->link = $link;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.video.download-request')
            ->text('mail.video.download-request_plain')
            ->subject('Approved Video Link Request');
    }
}
