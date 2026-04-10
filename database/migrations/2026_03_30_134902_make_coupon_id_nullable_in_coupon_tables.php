<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCouponIdNullableInCouponTables extends Migration
{
    public function up()
    {
        Schema::table('coupon_cart', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_cart', 'coupon_id')) {
                $table->unsignedBigInteger('coupon_id')->nullable()->change();
            }
        });

        Schema::table('coupon_sales', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_sales', 'coupon_id')) {
                $table->unsignedBigInteger('coupon_id')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('coupon_cart', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_cart', 'coupon_id')) {
                $table->unsignedBigInteger('coupon_id')->nullable(false)->change();
            }
        });

        Schema::table('coupon_sales', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_sales', 'coupon_id')) {
                 $table->unsignedBigInteger('coupon_id')->nullable(false)->change();
            }
        });
    }
}