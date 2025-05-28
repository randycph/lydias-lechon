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
            if (Schema::hasColumn('branches', 'comissary')) {
                $table->renameColumn('comissary', 'commissary');
            } else {
                $table->string('commissary')->nullable()->comment('Indicates if the branch is a comissar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            if (Schema::hasColumn('branches', 'commissary')) {
                $table->renameColumn('commissary', 'comissary');
            }
        });
    }
};
