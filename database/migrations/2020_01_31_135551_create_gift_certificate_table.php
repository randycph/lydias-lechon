<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftCertificateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_certificate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',150);
            $table->decimal('amount',16,4);
            $table->string('gc_type',100);
            $table->string('status', 20);
            $table->integer('user_id');
            $table->integer('sales_header_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_certificate');
    }
}
