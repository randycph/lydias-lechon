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
            $table->boolean('can_approve_payment')->default(false)->comment('Indicates if the role can approve payments');
            $table->boolean('has_branches')->default(false)->comment('Indicates if the role is associated with branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role', function (Blueprint $table) {
            $table->dropColumn('can_approve_payment');
            $table->dropColumn('has_branches');
        });
    }
};
