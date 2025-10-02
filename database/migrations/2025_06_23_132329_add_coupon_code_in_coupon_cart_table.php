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
        Schema::table('coupon_cart', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_cart', 'coupon_code')) {
                return;
            }
            $table->string('coupon_code')->nullable()->after('coupon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_cart', function (Blueprint $table) {
            $table->dropColumn('coupon_code');
        });
    }
};
