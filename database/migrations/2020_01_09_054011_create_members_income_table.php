<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members_income', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('rank');
            $table->integer('sponsor_id');
            $table->string('sponsor_rank');
            $table->integer('cutoff_id');
            $table->integer('personal_pv')->default(0);
            $table->integer('team_pv')->default(0);
            $table->decimal('retail_income',16,2)->default(0);
            $table->decimal('residual_income',16,2)->default(0);
            $table->decimal('rewards',16,2)->default(0);
            $table->decimal('rank_advancement_bonus',16,2)->default(0);
            $table->decimal('rebate_sales_bonus',16,2)->default(0);
            $table->decimal('rainmaker_achievement_bonus',16,2)->default(0);
            $table->decimal('regents_circle_bonus',16,2)->default(0);
            $table->decimal('royal_crown_bonus',16,2)->default(0);
            $table->decimal('replication_premium_incentive',16,2)->default(0);
            $table->decimal('retail_income_from_downline',16,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members_income');
    }
}
