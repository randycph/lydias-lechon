<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToGiftCert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gift_certificate', function (Blueprint $table) {
            
            $table->string('isApproved')->nullable();
            $table->string('approved_by')->nullable();
            $table->datetime('approved_on')->nullable();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gift_certificate', function (Blueprint $table) {
            //
        });
    }
}
