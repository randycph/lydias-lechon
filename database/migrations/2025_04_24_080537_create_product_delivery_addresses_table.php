<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('sale_id')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_tel')->nullable();
            $table->string('order')->nullable();
            $table->string('qty')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivery_time')->nullable();
            $table->integer('delivery_status')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_delivery_addresses');
    }
};
