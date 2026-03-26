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
            if (!Schema::hasColumn('ecommerce_sales_headers', 'delivery_branch')) {
                $table->string('delivery_branch')->nullable();
            }
            if (!Schema::hasColumn('ecommerce_sales_headers', 'origin')) {
                $table->string('origin')->nullable();
            }
            if (!Schema::hasColumn('ecommerce_sales_headers', 'forecast_date')) {
                $table->date('forecast_date')->nullable();
            }
            if (!Schema::hasColumn('ecommerce_sales_headers', 'customer_address')) {
                $table->text('customer_address')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            if (Schema::hasColumn('ecommerce_sales_headers', 'delivery_branch')) {
                $table->dropColumn('delivery_branch');
            }
            if (Schema::hasColumn('ecommerce_sales_headers', 'origin')) {
                $table->dropColumn('origin');
            }
            if (Schema::hasColumn('ecommerce_sales_headers', 'forecast_date')) {
                $table->dropColumn('forecast_date');
            }
            if (Schema::hasColumn('ecommerce_sales_headers', 'customer_address')) {
                $table->dropColumn('customer_address');
            }
        });
    }
};
