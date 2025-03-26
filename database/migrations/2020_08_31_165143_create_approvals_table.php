<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('approvals')) {
            return;
        }

        Schema::create('approvals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('approval_code');
            $table->integer('user_id');
            $table->string('approval_type')->default('Payment');
            $table->integer('reference_id');
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
    }
}
