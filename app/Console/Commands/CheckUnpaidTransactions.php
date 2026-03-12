<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EcommerceModel\SalesHeader;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckUnpaidTransactions extends Command
{
    protected $signature = 'transactions:check-unpaid';
    protected $description = 'Send email reminders for unpaid transactions and cancel if over 5 days';

    public function handle()
    {
        $now = Carbon::now();

        // Transactions unpaid for 2+ days (but less than 5)
        $remind = SalesHeader::where('payment_status', '!=', 'PAID')
            ->where('status', '!=', 'CANCELLED')
            ->whereDate('created_at', '<=', $now->copy()->subDays(2))
            ->whereDate('created_at', '>', $now->copy()->subDays(5))
            ->whereDate('created_at', '>=', '2025-12-29')
            ->get();

        foreach ($remind as $order) {
            if (!$order->user || !$order->user->email) {
                continue; // Skip if no user or email
            }
            Mail::to($order->user->email)->send(new \App\Mail\UnpaidReminderMail($order));
        }

        // Transactions unpaid for 5+ days — cancel them
        $cancel = SalesHeader::where('payment_status', '!=', 'PAID')
            ->where('status', '!=', 'CANCELLED')
            ->whereDate('created_at', '>=', '2025-12-29')

            // CANCEL CONDITIONS
            ->where(function ($q) use ($now) {

                // Case 1: delivery already passed
                $q->whereHas('items', function ($i) use ($now) {
                    $i->where('delivery_date', '<', $now);
                })

                // Case 2: future delivery but order older than 5 days
                ->orWhere(function ($q2) use ($now) {
                    $q2->whereDate('created_at', '<=', $now->copy()->subDays(5))
                    ->whereHas('items', function ($i) use ($now) {
                            $i->where('delivery_date', '>=', $now);
                    });
                });
            })

            // SIGN-CHIT EXCEPTION
            ->where(function ($q) use ($now) {

                // NOT Sign-Chit
                $q->whereDoesntHave('payments', function ($p) {
                    $p->where('payment_type', 'Sign-Chit');
                })

                // OR Sign-Chit but older than 30 days
                ->orWhere(function ($q2) use ($now) {
                    $q2->whereHas('payments', function ($p) {
                        $p->where('payment_type', 'Sign-Chit');
                    })
                    ->whereDate('created_at', '<=', $now->copy()->subDays(30));
                });
            })

            ->get();

        foreach ($cancel as $order) {
            $order->update(['status' => 'CANCELLED']);

            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->send(new \App\Mail\OrderCancelledMail($order));
            }
        }

        $this->info('Unpaid transaction reminders sent and expired orders cancelled.');
    }
}
