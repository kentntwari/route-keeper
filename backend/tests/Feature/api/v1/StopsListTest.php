<?php

namespace Tests\Feature;

use App\Models\Stop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StopsListTest extends TestCase
{
    use RefreshDatabase;

    public function test_stops_list_are_created_and_returned_correctly(): void
    {
        $stops = Stop::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/stops');

        $this->assertCount(3, $stops);
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }
}
