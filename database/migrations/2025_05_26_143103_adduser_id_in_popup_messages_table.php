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
            if (!Schema::hasColumn('popup_messages', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popup_messages', function (Blueprint $table) {
            if (Schema::hasColumn('popup_messages', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
