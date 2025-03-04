<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeftoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leftovers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('qty',16,2)->default(0);
            $table->integer('product_id')->default(0);
            $table->text('remarks')->nullable();
            $table->integer('user_id');
            $table->string('uom');
            $table->date('date');
            $table->integer('branch_id');
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
        Schema::dropIfExists('leftovers');
    }
}
