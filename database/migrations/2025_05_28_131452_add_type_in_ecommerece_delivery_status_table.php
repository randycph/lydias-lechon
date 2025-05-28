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
        Schema::table('ecommerce_delivery_status', function (Blueprint $table) {
            if (Schema::hasColumn('ecommerce_delivery_status', 'type')) {
                return;
            }
            if (Schema::hasColumn('ecommerce_delivery_status', 'job_order_id')) {
                return;
            }
            $table->string('type')->nullable()->default('sales')->comment('Type of delivery status, e.g., "sales" and "joborder"');
            $table->unsignedBigInteger('job_order_id')->nullable()->comment('Reference to job order if applicable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecommerce_delivery_status', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('job_order_id');
        });
    }
};
