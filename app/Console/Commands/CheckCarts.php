<?php

namespace App\Console\Commands;

use App\EcommerceModel\Cart;
use App\Mail\CartReminderMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckCarts extends Command
{
    protected $signature = 'cart:check';
    protected $description = 'Check carts for reminder and cleanup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $carts = Cart::with('user', 'product')->get();

        $cartsByUser = $carts->groupBy('user_id');

        foreach ($cartsByUser as $userId => $userCarts) {
            $user = $userCarts->first()->user;
    
            if (!$user || !$user->email) {
                continue;
            }
    
            // Find the oldest cart item to calculate how old their cart is
            $oldestCart = $userCarts->sortBy('created_at')->first();
            $created = Carbon::parse($oldestCart->created_at);
            $diffInDays = $created->diffInDays($now);
    
            if ($diffInDays >= 2) {
                // Send reminder email with all their cart items
                if ($user->receive_updates) {
                    Mail::to($user->email)->send(new CartReminderMail($userCarts));
                    logger('Reminder sent to ' . $user->email);
                }
            }

            if ($diffInDays >= 5) {
                // Delete all their cart items
                foreach ($userCarts as $cart) {
                    $cart->delete();
                }
                logger('Deleted carts for user ID ' . $userId);
            }
        }
    }

    // public function handle()
    // {
    //     $now = Carbon::now();

    //     $carts = Cart::with('user', 'product')->get();

    //     $cartsByUser = $carts->groupBy('user_id');

    //     foreach ($cartsByUser as $userId => $userCarts) {
    //         $user = $userCarts->first()->user;
    
    //         if (!$user || !$user->email) {
    //             continue;
    //         }
    
    //         // Find the oldest cart item to calculate how old their cart is
    //         $oldestCart = $userCarts->sortBy('created_at')->first();
    //         $created = Carbon::parse($oldestCart->created_at);
    //         $diffInHours = $created->diffInHours($now);

    //         if ($diffInHours >= 1) {
    //             Mail::to($user->email)->send(new CartReminderMail($userCarts));
    //             logger('Reminder sent to ' . $user->email);
    //         }

    //         if ($diffInHours >= 120) {
    //             foreach ($userCarts as $cart) {
    //                 $cart->delete();
    //             }
    //             logger('Deleted carts for user ID ' . $userId);
    //         }
    //     }
    // }
}
