<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SalesCompleted;
use App\Mail\SalesCompletedRegistered;
use App\Mail\SalesCompletedAdmin;
use App\Jobs\SendSmsJob;

class NotificationService
{
    public function send($salesHeader, $user, $request)
    {
        $recipient = $user->valid_email ?? $user->email ?? $request->email;

        // EMAILS (QUEUED AUTOMATICALLY)
        try {
            if ($user->registration_type === 'guest') {
                Mail::to($recipient)->queue(new SalesCompleted($salesHeader));
            } else {
                Mail::to($recipient)->queue(new SalesCompletedRegistered($salesHeader));
            }

            Mail::to(config('app.email'))->queue(new SalesCompletedAdmin($salesHeader));

        } catch (\Exception $e) {
            logger('email_error', [$e->getMessage()]);
        }

        // SMS (QUEUED JOB)
        if (!empty($salesHeader->customer_contact_number)) {
            SendSmsJob::dispatch(
                $salesHeader->customer_contact_number,
                'new_order',
                $salesHeader
            );
        }
    }
}