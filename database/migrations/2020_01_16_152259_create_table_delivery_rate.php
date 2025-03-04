<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDeliveryRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecommerce_delivery_rate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('region');
            $table->string('province');
            $table->string('municipality');
            $table->string('brgy')->nullable();
            $table->string('zip');
            $table->string('sla');
            $table->decimal('rate',16,2)->default(0);
            $table->string('remarks')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('ecommerce_delivery_rate');
    }
}
