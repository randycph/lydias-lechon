<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSentCampaignSubscribers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_campaign_subscribers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sent_campaign_id')->unsigned();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->bigInteger('subscriber_id')->unsigned();
            $table->string('mailing_type');
            $table->boolean('is_sent')->default(0);
            $table->timestamps();

            $table->foreign('sent_campaign_id')->references('id')->on('sent_campaigns');
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('subscriber_id')->references('id')->on('subscribers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sent_campaign_subscribers');
    }
}
