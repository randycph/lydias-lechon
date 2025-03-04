<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentDetailsOnPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_payments', function (Blueprint $table) {
            $table->string('order_number')->nullable();
            $table->string('remark',300)->nullable();
            $table->string('trans_id')->nullable();
            $table->string('err_desc',300)->nullable();
            $table->string('signature',300)->nullable();
            $table->string('cc_name',300)->nullable();
            $table->string('cc_no',300)->nullable();
            $table->string('bank_name',300)->nullable();
            $table->string('country',300)->nullable();
            
            $table->string('payment_date')->nullable()->change();
            $table->string('receipt_number')->nullable()->change();
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_sales_payments', function (Blueprint $table) {
            //
        });
    }
}
