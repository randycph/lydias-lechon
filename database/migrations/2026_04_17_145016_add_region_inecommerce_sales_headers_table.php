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
            if (Schema::hasColumn('ecommerce_sales_headers', 'region')) {
                return;
            }
            $table->string('region')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            if (!Schema::hasColumn('ecommerce_sales_headers', 'region')) {
                return;
            }
            $table->dropColumn('region');
        });
    }
};
