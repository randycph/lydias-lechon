<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWithInstallationFieldInEcommerceShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_shopping_cart', function (Blueprint $table) {
            $table->boolean('with_installation')->nullable()->after('qty');
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
