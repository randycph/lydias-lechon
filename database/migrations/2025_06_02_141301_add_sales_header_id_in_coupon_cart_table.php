<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('coupon_cart', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('coupon_id')->unsigned();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->integer('total_usage')->default(0);
            $table->string('status')->default('active');
            $table->decimal('discount_used', 16, 2)->default(0.00);
            $table->string('coupon_code')->nullable();
            $table->timestamps();
        });

        Schema::table('coupon_cart', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_cart', 'sales_header_id')) {
                $table->dropColumn('sales_header_id');
            }
        });

        Schema::table('coupon_cart', function (Blueprint $table) {
            if (!Schema::hasColumn('coupon_cart', 'sales_header_id')) {
                $table->unsignedBigInteger('sales_header_id')->nullable()->after('coupon_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupon_cart', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_cart', 'sales_header_id')) {
                $table->dropColumn('sales_header_id');
            }
        });
    }
};

