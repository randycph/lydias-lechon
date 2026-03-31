<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerIdToGiftCertificateTable extends Migration
{
    public function up()
    {
        Schema::table('gift_certificate', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('user_id');

            // If customer_id should reference users table:
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('gift_certificate', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
}