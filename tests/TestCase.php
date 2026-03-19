<?php

namespace Tests;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('DROP VIEW IF EXISTS view_role_permission');
        DB::statement('DROP VIEW IF EXISTS view_activity_logs');
        DB::statement('DROP VIEW IF EXISTS view_access_permission_per_role');
    }
}