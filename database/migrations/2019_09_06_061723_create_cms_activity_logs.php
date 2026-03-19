<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_activity_logs');

        DB::statement("
        CREATE VIEW view_activity_logs AS
            SELECT
                `l`.`id` AS `id`,
                `l`.`created_by` AS `created_by`,
                `l`.`activity_type` AS `activity_type`,
                `l`.`dashboard_activity` AS `dashboard_activity`,
                `l`.`activity_desc` AS `activity_desc`,
                `l`.`activity_date` AS `activity_date`,
                `l`.`db_table` AS `db_table`,
                `l`.`old_value` AS `old_value`,
                `l`.`new_value` AS `new_value`,
                `l`.`reference` AS `reference`,
                `u`.`email` AS `email`,
                `u`.`firstname` AS `firstname`,
                `u`.`lastname` AS `lastname`,
                `r`.`name` AS `role_name`
            FROM
                (
                    (
                        `lydias_db1_test`.`cms_activity_logs` `l`
                    LEFT JOIN `lydias_db1_test`.`users` `u`
                    ON
                        ((`u`.`id` = `l`.`created_by`))
                    )
                LEFT JOIN `lydias_db1_test`.`role` `r`
                ON
                    ((`r`.`id` = `u`.`role_id`))
                )

        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_activity_logs');
    }
};
