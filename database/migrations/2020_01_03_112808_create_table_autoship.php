<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAutoship extends Migration
{
    
    public function up()
    {
        Schema::create('ecommerce_autoship_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->integer('sales_id');
            $table->date('delivery_date');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('ecommerce_autoship_schedule');
    }
}
