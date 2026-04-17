<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EcommerceModel\SalesHeader;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckUnpaidTransactions extends Command
{
    protected $signature = 'transactions:check-unpaid';
    protected $description = 'Send email reminders for unpaid transactions and cancel if over 5 days';

    public function handle()
    {
        $now = Carbon::now();
        
        $statuses = ['ABANDONED', 'CANCELLED'];

        // Transactions unpaid for 2+ days (but less than 5)
        $remind = SalesHeader::where('payment_status', '!=', 'PAID')
            ->whereNotIn('status', $statuses)
            ->whereDate('created_at', '<=', $now->copy()->subDays(2))
            ->whereDate('created_at', '>', $now->copy()->subDays(5))
            ->whereDate('created_at', '>=', '2026-04-17')
            ->get();

        foreach ($remind as $order) {
            if (!$order->user || !$order->user->email) {
                continue; // Skip if no user or email
            }
            Mail::to($order->user->email)->send(new \App\Mail\UnpaidReminderMail($order));
        }

        // Transactions unpaid for 5+ days — cancel them
        $cancel = SalesHeader::where('payment_status', '!=', 'PAID')
            ->whereNotIn('status', $statuses)
            ->whereDate('created_at', '<=', $now->copy()->subDays(5))
            ->whereDate('created_at', '>=', '2026-04-17')

            ->where(function ($main) use ($now) {

                //(has_sub = 0)
                $main->where(function ($q) use ($now) {
                    $q->where('has_sub', 0)

                    ->where(function ($q) use ($now) {

                        // Case 1: delivery passed
                        $q->whereHas('items', function ($i) use ($now) {
                            $i->whereNotNull('delivery_date')
                                ->where('delivery_date', '!=', '0000-00-00 00:00:00')
                                ->where('delivery_date', '<', $now);
                        })

                        // Case 2: future delivery but older than 5 days
                        ->orWhere(function ($q2) use ($now) {
                            $q2->whereDate('created_at', '<=', $now->copy()->subDays(5))
                                ->whereHas('items', function ($i) use ($now) {
                                    $i->whereNotNull('delivery_date')
                                    ->where('delivery_date', '!=', '0000-00-00 00:00:00')
                                    ->where('delivery_date', '>=', $now);
                                });
                        });
                    });
                })

                //(has_sub = 1)
                ->orWhere(function ($q) {
                    $q->where('has_sub', 1)

                    // must have children
                    ->whereHas('subSales')

                    // no child should remain active
                    ->whereDoesntHave('subSales', function ($sub) {
                        $sub->whereNotIn('status', ['CANCELLED', 'ABANDONED']);
                    });
                });
            })

            //SIGN-CHIT
            ->where(function ($q) use ($now) {

                $q->whereDoesntHave('payments', function ($p) {
                    $p->where('payment_type', 'Sign-Chit');
                })

                ->orWhere(function ($q2) use ($now) {
                    $q2->whereHas('payments', function ($p) {
                        $p->where('payment_type', 'Sign-Chit');
                    })
                    ->whereDate('created_at', '<=', $now->copy()->subDays(30));
                });
            })

            ->get();

        foreach ($cancel as $order) {
            $order->update(['status' => 'ABANDONED']);

            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->send(new \App\Mail\OrderCancelledMail($order));
            }

            ActivityLog::create([
                'created_by'        => 1,
                'email'             => 'wsiprod.demo@gmail.com',
                'role'              => 'Super Admin',
                'dashboard_activity' => "abandoned order",
                'activity_desc'      => "updated the ecommerce_sales_headers status of {$order->id} from {$order->status} to 'ABANDONED",
                'activity_date'      => now()->format('Y-m-d H:i:s'),
                'db_table'           => 'ecommerce_sales_headers',
                'old_value'          => $order->status,
                'new_value'          => 'ABANDONED',
                'reference'          => $order->id,
                'subject_type'       => 'App\EcommerceModel\SalesHeader',
                'subject_id'         => $order->id,
                'ip_address'         => '',
                'session_id'         => null,
            ]);
        }

        $this->info('Unpaid transaction reminders sent and expired orders cancelled.');
    }
}
