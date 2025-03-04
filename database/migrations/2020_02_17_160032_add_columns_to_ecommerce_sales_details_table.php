<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToEcommerceSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_details', function (Blueprint $table) {
            $table->datetime('delivery_date')->nullable();
            $table->string('production_status')->nullable();
            $table->text('cancellation_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_sales_details', function (Blueprint $table) {
            //
        });
    }
}
