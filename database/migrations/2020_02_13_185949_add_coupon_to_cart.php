<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCouponToCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_shopping_cart', function (Blueprint $table) {
            $table->string('coupon_code',150)->nullable();
            $table->decimal('coupon_amount',16,4)->default(0.0000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_shopping_cart', function (Blueprint $table) {
            //
        });
    }
}
