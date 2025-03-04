<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JoborderAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->integer('paella_qty')->default(0);
            $table->decimal('paella_price',16,2)->default(0);
            $table->decimal('price',16,2)->default(0);
            $table->string('sales_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_orders', function (Blueprint $table) {
            //
        });
    }
}
