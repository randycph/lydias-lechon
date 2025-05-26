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
            $table->integer('start_to_show')->default(0)->after('is_active')->comment('Timestamp when the popup message should start showing');
            $table->string('button_text_url')->nullable()->after('button_text')->comment('Optional URL for the button text link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popup_messages', function (Blueprint $table) {
            $table->dropColumn('start_to_show');
            $table->dropColumn('button_text_url');
        });
    }
};
