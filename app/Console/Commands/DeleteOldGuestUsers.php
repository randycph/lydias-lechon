<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\EcommerceModel\SalesHeader;
use Carbon\Carbon;

class DeleteOldGuestUsers extends Command
{
    protected $signature = 'users:delete-old-guests';
    protected $description = 'Delete guest users older than 6 days without successful orders';

    public function handle()
    {
        $startDate = Carbon::create(2026, 3, 3);
        $today = Carbon::now();

        if ($today->lt($startDate)) {
            $this->info('Guest cleanup not started yet.');
            return;
        }

        $cutoff = Carbon::now()->subDays(40);

        $guests = User::where('registration_type', 'guest')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $cutoff)
            ->get();

        $deleted = 0;

        foreach ($guests as $guest) {

            $hasSuccessfulOrder = SalesHeader::where('user_id', $guest->id)
                ->where('payment_status', 'PAID')
                ->exists();

            if ($hasSuccessfulOrder) {
                continue;
            }

            // If no successful order OR only abandoned/cancelled orders
            $guest->delete();
            $deleted++;
        }

        logger()->info("Deleted {$deleted} old guest users without successful orders.");

        $this->info("Deleted {$deleted} guest users.");
    }
}