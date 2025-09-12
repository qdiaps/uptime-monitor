<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_returns_a_successful_response(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }
}
