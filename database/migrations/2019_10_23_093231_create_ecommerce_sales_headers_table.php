<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcommerceSalesHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecommerce_sales_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('order_number', 250);
            $table->text('response_code')->nullable();
            $table->string('order_source',250);
            $table->string('customer_name',250);
            $table->string('customer_contact_number',250);
            $table->text('customer_address');
            $table->text('customer_delivery_adress');
            $table->string('delivery_tracking_number',250);
            $table->decimal('delivery_fee_amount',16,4)->default(0);
            $table->decimal('gross_amount',16,4)->default(0);
            $table->decimal('tax_amount',16,4)->default(0);
            $table->decimal('net_amount',16,4)->default(0);
            $table->decimal('discount_amount',16,4)->default(0);
            $table->string('payment_status',250);
            $table->string('delivery_status',250);
            $table->string('status', 100);
            $table->string('payment_type', 250)->nullable();
            $table->date('payment_date')->default(date('Y-m-d'))->nullable();
            $table->string('receipt_number', 250)->nullable();
            $table->string('currency',100)->default('Php');;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecommerce_sales_headers');
    }
}
