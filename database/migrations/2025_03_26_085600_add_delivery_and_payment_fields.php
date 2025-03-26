<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'disable_pickup_dates')) {
                $table->text('disable_pickup_dates')->nullable()->after('minimum_order_pickup');
            }
            if (!Schema::hasColumn('settings', 'disable_delivery_dates')) {
                $table->text('disable_delivery_dates')->nullable()->after('disable_pickup_dates');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'allowed_payments')) {
                $table->text('allowed_payments')->nullable()->after('is_subscribe');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['disable_pickup_dates', 'disable_delivery_dates']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('allowed_payments');
        });
    }
};
