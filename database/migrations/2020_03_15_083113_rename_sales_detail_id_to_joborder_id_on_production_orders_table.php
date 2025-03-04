<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSalesDetailIdToJoborderIdOnProductionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->renameColumn('sales_detail_id', 'joborder_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->renameColumn('joborder_id', 'sales_detail_id');
        });
    }
}
