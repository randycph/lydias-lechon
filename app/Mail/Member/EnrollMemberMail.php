<?php

namespace App\Mail\Member;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnrollMemberMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $user;
    public $sponsor;
    public $token;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $user
     * @param $sponsor
     * @param $token
     */
    public function __construct($setting, $user, $sponsor, $token)
    {
        $this->setting = $setting;
        $this->user = $user;
        $this->sponsor = $sponsor;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.member.enroll-member')
            ->text('mail.member.enroll-member_plain')
            ->subject('Enrolled by existing member');
    }
}
