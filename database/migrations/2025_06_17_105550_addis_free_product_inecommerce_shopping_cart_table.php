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
        Schema::table('ecommerce_shopping_cart', function (Blueprint $table) {
            $table->boolean('is_free_product')->default(false)->comment('Indicates if the product is free');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecommerce_shopping_cart', function (Blueprint $table) {
            $table->dropColumn('is_free_product');
        });
    }
};
