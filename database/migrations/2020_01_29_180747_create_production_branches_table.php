<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('address_region');
            $table->string('address_province');
            $table->string('address_city');
            $table->string('address_street');
            $table->string('address_zip');
            $table->string('contact_tel');
            $table->string('contact_mobile');
            $table->string('contact_person');
            $table->integer('created_by');
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
        Schema::dropIfExists('production_branches');
    }
}
