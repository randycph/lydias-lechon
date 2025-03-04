<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAutoshipDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecommerce_autoship_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('autoship_header_id');
            $table->bigInteger('product_id');
            $table->decimal('price',16,4);
            $table->integer('qty');
            $table->string('uom',50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecommerce_autoship_detail');
    }
}
