<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupon_cart', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_cart', 'sales_header_id')) {
                $table->dropColumn('sales_header_id');
            }
        });

        Schema::table('coupon_cart', function (Blueprint $table) {
            if (!Schema::hasColumn('coupon_cart', 'sales_header_id')) {
                $table->unsignedBigInteger('sales_header_id')->nullable()->after('coupon_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupon_cart', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_cart', 'sales_header_id')) {
                $table->dropColumn('sales_header_id');
            }
        });
    }
};

