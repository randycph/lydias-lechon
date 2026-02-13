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
        Schema::create('blocked_slots', function (Blueprint $table) {
            $table->id();

            // Scope
            $table->enum('scope', ['all', 'category', 'product']);

            $table->integer('category_id')->nullable();
            $table->integer('product_id')->nullable();

            // Date & time
            $table->date('date');
            $table->time('start_time')->nullable(); // null = all day
            $table->time('end_time')->nullable();

            $table->boolean('is_all_day')->default(false);

            $table->timestamps();

            // Safety indexes
            $table->index(['date']);
            $table->index(['scope']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_slots');
    }
};
