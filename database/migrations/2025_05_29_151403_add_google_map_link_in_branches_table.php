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
        Schema::table('branches', function (Blueprint $table) {
            $table->text('google_map_link')->nullable()->comment('Google Map link for the branch location');
            $table->text('direction_link')->nullable()->comment('Text to display for the Google Map link');
        });

        Schema::table('branch_numbers', function (Blueprint $table) {
            $table->string('type')->nullable()->comment('Type of branch number, e.g., hotline, fax, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['google_map_link', 'direction_link']);
        });

        Schema::table('branch_numbers', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
