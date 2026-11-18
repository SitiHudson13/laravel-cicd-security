<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
| Pest bootstrap for the SecureProject labs.
| Feature tests run against the in-memory SQLite database defined in phpunit.xml,
| never against the lab's seeded database file.
*/
pest()->extend(TestCase::class)->use(RefreshDatabase::class)->in('Feature');
