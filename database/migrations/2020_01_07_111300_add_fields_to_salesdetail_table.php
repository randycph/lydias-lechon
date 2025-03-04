<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSalesdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ecommerce_sales_details', function (Blueprint $table) {
            $table->integer('pv')->default(0);
            $table->integer('pv_gross')->default(0);
            $table->decimal('cv',16,2)->default(0);
            $table->decimal('cv_gross',16,2)->default(0);
            $table->decimal('other_cost',16,2)->default(0);
            $table->string('other_cost_description')->nullable();

            $table->decimal('retail_income',16,2)->default(0);
            $table->decimal('residual_income',16,2)->default(0);
            $table->decimal('rewards',16,2)->default(0);
            $table->decimal('rank_advancement_bonus',16,2)->default(0);
            $table->decimal('rebate_sales_bonus',16,2)->default(0);
            $table->decimal('rainmaker_achievement_bonus',16,2)->default(0);
            $table->decimal('regents_circle_bonus',16,2)->default(0);
            $table->decimal('royal_crown_bonus',16,2)->default(0);
            $table->decimal('replication_premium_incentive',16,2)->default(0);
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
            $table->dropColumn('pv');
            $table->dropColumn('pv_gross');
            $table->dropColumn('cv');
            $table->dropColumn('cv_gross');
            $table->dropColumn('other_cost');
            $table->dropColumn('other_cost_description');
        });
    }
}
