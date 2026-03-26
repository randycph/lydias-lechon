<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_access_permission_per_role');

        DB::statement("
            CREATE VIEW view_access_permission_per_role AS
            SELECT
                vrp.user_id,
                vrp.role,
                GROUP_CONCAT(vrp.name SEPARATOR '|') AS permissions
            FROM view_role_permission vrp
            GROUP BY vrp.user_id, vrp.role
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_access_permission_per_role');
    }
};
