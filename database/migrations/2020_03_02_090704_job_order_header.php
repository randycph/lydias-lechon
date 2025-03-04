<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobOrderHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('jo_number', 250);
            $table->string('sales_number', 250);
            $table->text('sales_detail_id');
            $table->string('order_source',250);
            $table->bigInteger('product_id');
            $table->string('product_name', 250);
            $table->string('product_size');
            $table->string('product_weight');
            $table->string('product_category');
            $table->string('customer_name',250);
            $table->datetime('date_needed');
            $table->string('customer_mobile_number',250);
            $table->string('customer_tel_number',250)->nullable();
            $table->text('customer_address')->nullable();
            $table->text('customer_delivery_adress');
            $table->string('delivery_tracking_number',250);      
            $table->string('delivery_method');       
            $table->string('pickup_branch')->nullable();       
            $table->string('delivery_status',250)->nullable();
            $table->string('status', 100);      
            $table->string('jo_category',250);    
            $table->string('jo_schedule_type',250);    
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
        //
    }
}
