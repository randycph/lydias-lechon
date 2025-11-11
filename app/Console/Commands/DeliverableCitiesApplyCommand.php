<?php

namespace App\Console\Commands;

use App\Models\Deliverablecities;
use Illuminate\Console\Command;

class DeliverableCitiesApplyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:deliverable-cities-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Deliverablecities::query()->chunkById(500, function($batch) {
            foreach ($batch as $thing) {
                $before = $thing->is_active;
                $now    = now();

                // Clear expired override
                if ($thing->override_until && $now->gte($thing->override_until)) {
                    $thing->override_state = null;
                    $thing->override_until = null;
                }

                // Apply one-shot schedules
                if ($thing->auto_on_at && $now->gte($thing->auto_on_at)) {
                    $thing->is_active   = true;
                    $thing->auto_on_at  = null;
                }
                if ($thing->auto_off_at && $now->gte($thing->auto_off_at)) {
                    $thing->is_active    = false;
                    $thing->auto_off_at  = null;
                }

                if ($thing->is_active !== $before) {
                    $thing->last_changed_at = now();
                }

                if ($thing->isDirty()) $thing->save();
            }
        });
    }
}
