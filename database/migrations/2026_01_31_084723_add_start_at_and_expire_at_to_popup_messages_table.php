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
        Schema::table('popup_messages', function (Blueprint $table) {
            $table->timestamp('start_at')->nullable()->after('is_active');
            $table->timestamp('expire_at')->nullable()->after('start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popup_messages', function (Blueprint $table) {
            $table->dropColumn('start_at');
            $table->dropColumn('expire_at');
        });
    }
};
