<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function getTokenByUser()
    {
        $this->assertTrue(true);
    }
}
