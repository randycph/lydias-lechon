<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSentCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->string('from_name');
            $table->string('from_email');
            $table->string('subject');
            $table->text('content');
            $table->timestamps();

            //$table->foreign('sender_id')->references('id')->on('users');
            //$table->foreign('campaign_id')->references('id')->on('campaigns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sent_campaigns');
    }
}
