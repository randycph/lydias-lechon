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
        Schema::table('ecommerce_sales_details', function (Blueprint $table) {
            $table->boolean('has_baka')->default(false)->nullable();
            $table->float('lechon_baka_service')->default(0)->nullable();
        });

        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->boolean('has_baka')->default(false)->nullable();
            $table->float('lechon_baka_service')->default(0)->nullable();
        });

        Schema::table('product_delivery_addresses', function (Blueprint $table) {
            $table->boolean('has_baka')->default(false)->nullable();
            $table->float('lechon_baka_service')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
