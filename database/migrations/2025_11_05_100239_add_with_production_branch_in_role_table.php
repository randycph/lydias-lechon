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
        Schema::table('role', function (Blueprint $table) {
            $table->boolean('has_production_branch')->default(false)->comment('Indicates if the role is associated with production branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role', function (Blueprint $table) {
            //
        });
    }
};
