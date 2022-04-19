<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // * setUp
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed --class=TestSeeder');

        // Artisan::call('love:reaction-type-add --default');
    }
}
