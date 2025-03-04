<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsHasSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_has_subscribers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('subscriber_id')->unsigned();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');;
            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_has_subscribers');
    }
}
