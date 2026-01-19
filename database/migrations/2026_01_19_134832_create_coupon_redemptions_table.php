<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_redemptions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('coupon_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('order_id')->nullable();

            $table->timestamp('redeemed_at')->useCurrent();

            $table->enum('status', ['success', 'failed'])->default('success');
            $table->text('notes')->nullable();

            $table->index('coupon_id');

            // Optional but recommended foreign keys
            // $table->foreign('coupon_id')->references('id')->on('coupon_new')->cascadeOnDelete();
            // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            // $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_redemptions');
    }
};
