<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstallationFeeInEcommerceShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_shopping_cart', function (Blueprint $table) {
            $table->decimal('installation_fee',16,4)->after('with_installation')->nullable();
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
