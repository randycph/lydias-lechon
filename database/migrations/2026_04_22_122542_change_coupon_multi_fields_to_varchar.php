<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE coupons MODIFY purchase_product_id VARCHAR(1000) NULL");
        DB::statement("ALTER TABLE coupons MODIFY purchase_product_cat_id VARCHAR(1000) NULL");
        DB::statement("ALTER TABLE coupons MODIFY purchase_product_brand VARCHAR(1000) NULL");
        DB::statement("ALTER TABLE coupons MODIFY scope_customer_id VARCHAR(1000) NULL");
        DB::statement("ALTER TABLE coupons MODIFY free_product_id VARCHAR(1000) NULL");
        DB::statement("ALTER TABLE coupons MODIFY location VARCHAR(1000) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE coupons MODIFY purchase_product_id BIGINT NULL");
        DB::statement("ALTER TABLE coupons MODIFY purchase_product_cat_id BIGINT NULL");
        DB::statement("ALTER TABLE coupons MODIFY purchase_product_brand BIGINT NULL");
        DB::statement("ALTER TABLE coupons MODIFY scope_customer_id BIGINT NULL");
        DB::statement("ALTER TABLE coupons MODIFY free_product_id BIGINT NULL");
        DB::statement("ALTER TABLE coupons MODIFY location VARCHAR(191) NULL");
    }
};
