<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSalesheaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->string('member_rank')->nullable();
            $table->integer('member_rank_seq')->nullable();
            $table->integer('sponsor_id')->nullable();
            $table->string('sponsor_rank')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_sales_headers', function (Blueprint $table) {
            $table->dropColumn('member_rank');
            $table->dropColumn('member_rank_seq');
            $table->dropColumn('sponsor_id');
            $table->dropColumn('sponsor_rank');
        });
    }
}
