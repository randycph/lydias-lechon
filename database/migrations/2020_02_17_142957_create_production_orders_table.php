<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id');
            $table->integer('sales_detail_id');
            $table->decimal('qty',16,2);
            $table->datetime('delivery_date');
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
        Schema::dropIfExists('production_orders');
    }
}
