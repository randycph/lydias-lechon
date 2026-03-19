<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_role_permission');

        Schema::create('cms_activity_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('created_by')->nullable();
            $table->string('activity_type', 50)->nullable();
            $table->string('dashboard_activity')->default(false);
            $table->text('activity_desc')->nullable();
            $table->dateTime('activity_date')->nullable();
            $table->string('db_table', 50)->nullable();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('reference', 255)->nullable();
            $table->timestamps();
        });

        DB::statement("
        CREATE VIEW view_role_permission AS
            SELECT
                `lydias_db1_test`.`role_permission`.`user_id` AS `user_id`,
                `lydias_db1_test`.`role_permission`.`role_id` AS `role`,
                `lydias_db1_test`.`permission`.`name` AS `name`,
                `lydias_db1_test`.`permission`.`module` AS `permission_module`
            FROM
                (
                    `lydias_db1_test`.`role_permission`
                JOIN `lydias_db1_test`.`permission` ON
                    (
                        (
                            `lydias_db1_test`.`role_permission`.`permission_id` = `lydias_db1_test`.`permission`.`id`
                        )
                    )
                )
            WHERE
                (
                    `lydias_db1_test`.`role_permission`.`isAllowed` = 1
                )

        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_activity_logs');
        DB::statement('DROP VIEW IF EXISTS view_role_permission');
    }
};
