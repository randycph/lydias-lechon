<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordLink extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Your Password')
            ->markdown('emails.auth.reset-password', [
                'url' => url(route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)),
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ]);
    }
}
