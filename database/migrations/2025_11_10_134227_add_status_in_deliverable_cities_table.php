<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deliverable_cities', function (Blueprint $table) {
            // Current effective state
            $table->boolean('is_active')->default(true);

            // Who decides the next state
            $table->string('control_mode')->nullable();

            // Manual override with auto-revert
            // true = force on, false = force off
            $table->boolean('override_state')->nullable();
            $table->timestamp('override_until')->nullable();

            // One-shot scheduled flip(s)
            $table->timestamp('auto_on_at')->nullable();
            $table->timestamp('auto_off_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliverable_cities', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'control_mode',
                'override_state',
                'override_until',
                'auto_on_at',
                'auto_off_at'
            ]);
        });
    }
};
