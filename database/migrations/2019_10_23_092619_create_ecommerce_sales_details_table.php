<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcommerceSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecommerce_sales_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sales_header_id');
            $table->bigInteger('product_id');
            $table->string('product_name', 250);
            $table->string('product_category', 250);
            $table->decimal('price',16,4);
            $table->decimal('paella_price',16,4);
            $table->decimal('cost',16,4);
            $table->decimal('tax_amount',16,4);
            $table->bigInteger('promo_id');
            $table->text('promo_description');
            $table->decimal('discount_amount',16,4);
            $table->decimal('gross_amount',16,4);
            $table->decimal('net_amount',16,4);
            $table->decimal('qty',16,2);
            $table->string('uom',50);
            $table->decimal('penalty');
            $table->string('status')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('ecommerce_sales_details');
    }
}
