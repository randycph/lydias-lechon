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
        Schema::table('product_delivery_addresses', function (Blueprint $table) {
            $table->decimal('delivery_fee', 8, 2)->default(0)->after('delivery_status');
            $table->string('location')->nullable()->after('delivery_fee');
            $table->string('branch')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_delivery_addresses', function (Blueprint $table) {
            //
        });
    }
};
