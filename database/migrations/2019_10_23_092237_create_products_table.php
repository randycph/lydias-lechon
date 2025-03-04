<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->nullable();
            $table->string('name', 250);
            $table->text('slug');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('currency', 30);
            $table->decimal('price',16,4)->nullable();
            $table->string('size', 30)->nullable();
            $table->string('weight')->nullable();
            $table->string('no_of_pax', 250)->nullable();
            $table->boolean('for_sale')->nullable();
            $table->string('status',100);
            $table->string('uom',30)->default('PC');
            $table->boolean('is_featured')->default(false);
            $table->integer('created_by');
            $table->string('meta_title', 150)->nullable();
            $table->string('meta_keyword', 150)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('code', 250)->nullable();
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
        Schema::dropIfExists('products_');
    }
}
