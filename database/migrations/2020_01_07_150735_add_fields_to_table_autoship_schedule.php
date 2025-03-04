<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTableAutoshipSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_autoship_schedule', function (Blueprint $table) {
            $table->string('status');
            $table->string('schedule_type');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_autoship_schedule', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('schedule_type');  
        });
    }
}
