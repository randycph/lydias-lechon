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
        Schema::create('popup_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Title of the popup message');
            $table->text('message')->comment('Content of the popup message');
            $table->string('button_text')->default('OK')->comment('Text for the button in the popup');
            $table->string('close_button_text')->default('Close')->comment('Text for the close button in the popup');
            $table->boolean('is_active')->default(true)->comment('Indicates if the popup message is active');
            $table->string('url')->nullable()->comment('Optional URL to redirect when the button is clicked');
            $table->string('image')->nullable()->comment('Optional image URL to display in the popup');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popup_messages');
    }
};
