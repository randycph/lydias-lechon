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
            Mail::to($order->user->email)->send(new \App\Mail\UnpaidReminderMail($order));
        }

        // Transactions unpaid for 5+ days — cancel them
        $cancel = SalesHeader::where('payment_status', '!=', 'PAID')
            ->where('status', '!=', 'CANCELLED')
            ->whereDate('created_at', '<=', $now->copy()->subDays(5))
            ->whereDate('created_at', '>=', '2025-12-29')
            ->get();

        foreach ($cancel as $order) {
            $order->update(['status' => 'CANCELLED']);

            Mail::to($order->user->email)->send(new \App\Mail\OrderCancelledMail($order));
        }

        $this->info('Unpaid transaction reminders sent and expired orders cancelled.');
    }
}
