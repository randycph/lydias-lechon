<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class)->in('Feature', 'Unit');

// Optional but recommended
uses(RefreshDatabase::class)->in('Feature');