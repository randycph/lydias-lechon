<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreferredDatesInServiceRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_request', function (Blueprint $table) {
            $table->date('preferred_date1')->nullable();
            $table->date('preferred_date2')->nullable();
            $table->string('status');
            $table->datetime('scheduled_date')->nullable();
            $table->integer('message_id')->nullable();
                  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_request', function (Blueprint $table) {
            //
        });
    }
}
