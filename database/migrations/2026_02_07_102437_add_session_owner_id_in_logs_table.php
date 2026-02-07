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
        Schema::table('cms_activity_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('session_owner_id')->nullable()->after('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_activity_logs', function (Blueprint $table) {
            $table->dropColumn('session_owner_id');
        });
    }
};
