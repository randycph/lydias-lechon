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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code')->unique();
            $table->string('reward')->nullable()->comment('Reward for the coupon');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->string('activation_type')->default('manual'); // manual or automatic
            $table->string('customer_scope')->default('all'); // all or specific
            $table->string('scope_customer_id')->nullable();
            $table->string('location')->default('all'); // all or specific
            $table->string('location_discount_type')->nullable(); // product, category, brand
            $table->decimal('location_discount_amount', 16, 2)->default(0.00);
            $table->decimal('amount', 16, 2)->default(0.00);
            $table->integer('percentage')->nullable();
            $table->string('free_product_id')->nullable();
            $table->string('status')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('event_name')->nullable();
            $table->date('event_date')->nullable();
            $table->integer('repeat_annually')->nullable();
            $table->bigInteger('purchase_product_id')->unsigned()->nullable();
            $table->bigInteger('purchase_product_cat_id')->unsigned()->nullable();
            $table->bigInteger('purchase_product_brand')->unsigned()->nullable();
            $table->decimal('purchase_amount', 16, 2)->default(0.00);
            $table->string('purchase_amount_type')->default('none'); // none, minimum, maximum
            $table->integer('purchase_qty')->default(0);
            $table->string('purchase_qty_type')->nullable(); // none, minimum, maximum
            $table->integer('purchase_combination_counter')->default(0);
            $table->text('purchase_combination')->nullable(); // JSON array of product IDs
            $table->string('activity_type')->nullable(); // all, new_customers,
            $table->integer('customer_limit')->nullable(); // 0 for unlimited
            $table->string('usage_limit')->nullable(); // unlimited or limited
            $table->integer('usage_limit_no')->nullable(); // number of times the coupon can be used if limited
            $table->string('combination')->nullable(); // none, product, category, brand
            $table->string('availability')->default(0); // public or private
            $table->bigInteger('user_id')->unsigned(); // creator of the coupon
            $table->string('product_discount')->nullable(); // whether the coupon gives a product discount
            $table->bigInteger('discount_product_id')->unsigned()->nullable(); // product ID for product discount
            $table->timestamps();
        });

        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'reward')) {
                return;
            }
            $table->string('reward')->nullable()->comment('Reward for the coupon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'reward')) {
                return;
            }
            $table->dropColumn('reward');
        });
    }
};
