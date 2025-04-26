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
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->boolean('is_multiple_address')->default(false);
        });

        Schema::table('product_delivery_addresses', function (Blueprint $table) {
            $table->bigInteger('sales_header_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->dropColumn('is_multiple_address');
        });

        Schema::table('product_delivery_addresses', function (Blueprint $table) {
            $table->dropColumn('sales_header_id');
        });
    }
};
