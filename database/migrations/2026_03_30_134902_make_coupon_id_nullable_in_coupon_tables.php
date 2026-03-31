<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCouponIdNullableInCouponTables extends Migration
{
    public function up()
    {
        Schema::table('coupon_cart', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id')->nullable()->change();
        });

        Schema::table('coupon_sales', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('coupon_cart', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id')->nullable(false)->change();
        });

        Schema::table('coupon_sales', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id')->nullable(false)->change();
        });
    }
}