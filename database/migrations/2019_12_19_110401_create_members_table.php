<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unique();
            $table->integer('sponsor_id')->nullable();
            $table->string('code', 40)->unique();
            $table->string('entity_type', 150);

            $table->string('government_id_type', 150)->nullable();
            $table->string('government_id', 150)->nullable();
            $table->text('government_id_photo')->nullable();


            $table->string('first_name', 150);
            $table->string('middle_name', 150)->nullable();
            $table->string('last_name', 150);
            $table->date('birthday')->nullable();

            $table->string('email', 200);
            $table->string('mobile', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('work_phone', 50)->nullable();
            $table->string('fax', 50)->nullable();

            $table->string('address_street', 250)->nullable();
            $table->string('address_city', 150)->nullable();
            $table->string('address_province', 150)->nullable();
            $table->string('address_zip', 10)->nullable();
            $table->string('address_country', 150)->nullable();

            $table->string('address_delivery_street', 250)->nullable();
            $table->string('address_delivery_city', 150)->nullable();
            $table->string('address_delivery_province', 150)->nullable();
            $table->string('address_delivery_zip', 10)->nullable();
            $table->string('address_delivery_country', 150)->nullable();

            $table->string('security_question', 150)->nullable();
            $table->string('security_answer', 150)->nullable();

            $table->string('status', 50);
            $table->string('class', 150)->nullable();

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
        Schema::dropIfExists('members');
    }
}
