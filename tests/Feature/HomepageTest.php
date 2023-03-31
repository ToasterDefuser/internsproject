<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function HomepageTest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
