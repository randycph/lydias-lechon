<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsToSalesDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_details', function (Blueprint $table) {
            $table->dropColumn('retail_income');
            $table->dropColumn('residual_income');
            $table->dropColumn('rewards');
            $table->dropColumn('rank_advancement_bonus');
            $table->dropColumn('rebate_sales_bonus');
            $table->dropColumn('rainmaker_achievement_bonus');
            $table->dropColumn('regents_circle_bonus');
            $table->dropColumn('royal_crown_bonus');
            $table->dropColumn('replication_premium_incentive');
            $table->dropColumn('pv');
            $table->dropColumn('pv_gross');
            $table->dropColumn('cv');
            $table->dropColumn('cv_gross');
            $table->dropColumn('retail_income_from_downline');
            $table->string('size');
            $table->string('no_of_pax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecommerce_sales_details', function (Blueprint $table) {
            //
        });
    }
}
