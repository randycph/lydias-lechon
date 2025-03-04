<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCutoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutoffs', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->integer('month');
            $table->integer('year');
            $table->datetime('generated')->nullable();
            $table->integer('generated_by')->nullable();
            $table->datetime('posted')->nullable();
            $table->integer('posted_by')->nullable();            
            $table->string('status', 100)->default('Pending');
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
        Schema::dropIfExists('cutoffs');
    }
}
