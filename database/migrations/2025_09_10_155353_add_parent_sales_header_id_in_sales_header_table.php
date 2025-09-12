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
            $table->unsignedBigInteger('parent_sales_header_id')->nullable()->after('id');
            $table->tinyInteger('has_dispatched')->default(0)->after('parent_sales_header_id');
            $table->tinyInteger('has_transited')->default(0)->after('has_dispatched');
            $table->tinyInteger('is_new_order')->default(0)->after('has_transited');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->dropColumn('parent_sales_header_id');
            $table->dropColumn('has_dispatched');
            $table->dropColumn('has_transited');
        });
    }
};
