<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsConfirmToSalesHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->integer('isConfirm')->default(0);
            $table->string('confirmed_by')->nullable();
            $table->datetime('confirmed_on')->nullable();
            $table->text('confirm_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            //
        });
    }
}
