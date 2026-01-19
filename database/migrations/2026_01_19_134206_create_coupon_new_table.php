<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_new', function (Blueprint $table) {
            $table->increments('id');

            $table->string('coupon_name', 150);
            $table->text('coupon_desc')->nullable();
            $table->string('code', 50)->unique();

            $table->enum('discount_type', ['percentage', 'fixed', 'free delivery']);
            $table->decimal('discount_value', 10, 2);

            $table->decimal('min_spend', 10, 2)->default(0.00);
            $table->decimal('max_discount', 10, 2)->default(0.00);

            $table->integer('usage_limit')->nullable()->default(1);
            $table->integer('usage_per_user')->nullable()->default(1);

            $table->enum('is_auto_apply', ['Yes', 'No'])->nullable();

            $table->unsignedInteger('product_id')->nullable();

            $table->string('region_code', 150)->nullable();
            $table->string('province_code', 150)->nullable();
            $table->string('city_code', 150)->nullable();
            $table->string('barangay_code', 150)->nullable();

            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();

            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_new');
    }
};
