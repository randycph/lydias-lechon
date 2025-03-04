<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesHeaderFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->text('instruction')->nullable();
            $table->string('agent')->nullable();
            $table->string('customer_location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_sales_header', function (Blueprint $table) {
            //
        });
    }
}
