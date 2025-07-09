<?php

namespace App\Console\Commands;

use App\EcommerceModel\Cart;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\CronJobTestMail;

class SendCronTestEmail extends Command
{
    protected $signature = 'cron:test-email';
    protected $description = 'Send a dummy email every 10 minutes to test the cron';

    public function handle()
    {
        // Replace with your desired test email
        $carts = Cart::with('user', 'product')->get();

        $cartsByUser = $carts->groupBy('user_id');

        foreach ($cartsByUser as $userId => $userCarts) {
            $user = $userCarts->first()->user;
    
            if (!$user || !$user->email) {
                continue;
            }
    
            Mail::to($user)->send(new CronJobTestMail());
        }
    }
}
