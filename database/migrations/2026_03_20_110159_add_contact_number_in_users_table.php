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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'contact_mobile')) {
                $table->string('contact_mobile')->nullable();
            }
            if (!Schema::hasColumn('users', 'registration_source')) {
                $table->string('registration_source')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'contact_mobile')) {
                $table->dropColumn('contact_mobile');
            }
            if (Schema::hasColumn('users', 'registration_source')) {
                $table->dropColumn('registration_source');
            }
        });
    }
};
