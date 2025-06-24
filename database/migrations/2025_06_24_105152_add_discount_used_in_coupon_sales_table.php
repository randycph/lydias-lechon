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
        Schema::table('coupon_sales', function (Blueprint $table) {
            $table->decimal('discount_used', 10, 2)->default(0.00)->after('coupon_code')->comment('The amount of discount used from the coupon');
        });

        Schema::table('coupon_cart', function (Blueprint $table) {
            $table->decimal('discount_used', 10, 2)->default(0.00)->after('coupon_code')->comment('The amount of discount used from the coupon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_sales', function (Blueprint $table) {
            $table->dropColumn('discount_used');
        });

        Schema::table('coupon_cart', function (Blueprint $table) {
            $table->dropColumn('discount_used');
        });
    }
};
