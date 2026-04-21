<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('location', 191)->nullable()->default(null)->change();
            $table->string('location_discount_type', 191)->nullable()->default(null)->change();
            $table->decimal('location_discount_amount', 10, 2)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('location', 191)->default('all')->nullable(false)->change();
            $table->string('location_discount_type', 191)->nullable(false)->change();
            $table->decimal('location_discount_amount', 10, 2)->default(0)->nullable(false)->change();
        });
    }
};
