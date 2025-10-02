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
        // protected $fillable = [ 'customer_id', 'coupon_id', 'coupon_code', 'sales_header_id', 'order_status','product_id', 'discount_used'];

        // check if coupon sales alteady exists
        if (!Schema::hasTable('coupon_sales')) {
            Schema::create('coupon_sales', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('customer_id')->unsigned();
                $table->bigInteger('coupon_id')->unsigned();
                $table->string('coupon_code')->nullable();
                $table->bigInteger('sales_header_id')->unsigned()->nullable();
                $table->string('order_status')->default('UNPAID');
                $table->bigInteger('product_id')->unsigned()->nullable();
                $table->timestamps();
            });
        }

        Schema::table('coupon_sales', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_sales', 'discount_used')) {
                return;
            }
            $table->decimal('discount_used', 10, 2)->default(0.00)->after('coupon_code')->comment('The amount of discount used from the coupon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_sales', function (Blueprint $table) {
            $table->dropColumn('discount_used');
        });

        Schema::table('coupon_cart', function (Blueprint $table) {
            $table->dropColumn('discount_used');
        });
    }
};
