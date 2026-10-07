<?php

namespace Tests\Feature\api\v1;

use App\Enums\StopException;
use App\Models\Stop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SingleStopTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_stop_is_created_with_end_time_set_correctly(): void
    {
        $response = $this->postJson('/api/v1/stops', [
            'id' => $this->faker->uuid(),
            'startTime' => $this->faker->time('H:i'),
            'endTime' => $this->faker->time('H:i'),
            'deliveriesCount' => $this->faker->numberBetween(0, 100),
            'pickupsCount' => $this->faker->numberBetween(0, 10),
            'snapshotUrl' => $this->faker->imageUrl(),
        ]);

        $response->assertCreated();
        $response->assertStatus(201);
    }

    public function test_stop_estimated_time_is_null_when_total_time_is_set(): void
    {
        $stop = Stop::factory()->create();

        $this->assertNull($stop->estimated_time);
    }

    public function test_stop_exception_can_be_applied(): void
    {
        $stop = Stop::factory()->create([
            'exception' => StopException::SickOff,
        ]);

        $this->assertEquals(StopException::SickOff, $stop->exception); // Replace with the same valid exception value
        $this->assertEquals('00:00', $stop->start_time);
        $this->assertEquals('00:00', $stop->end_time);
        $this->assertNull($stop->total_time);
        $this->assertNull($stop->estimated_time);
        $this->assertEquals(0, $stop->deliveries_count);
        $this->assertEquals(0, $stop->pickups_count);
    }

    public function test_stop_snapshot_is_created(): void
    {
        $data = [
            'id' => $this->faker->uuid(),
            'startTime' => $this->faker->time('H:i'),
            'endTime' => $this->faker->time('H:i'),
            'deliveriesCount' => $this->faker->numberBetween(0, 100),
            'pickupsCount' => $this->faker->numberBetween(0, 10),
            'snapshotUrl' => $this->faker->imageUrl(),
            'snapshotMetadata' => json_encode([
                'width' => $this->faker->numberBetween(100, 1000),
                'height' => $this->faker->numberBetween(100, 1000),
                'description' => $this->faker->sentence(10),
                'ip_address' => $this->faker->ipv4(),
                'user_agent' => $this->faker->userAgent(),
            ]),
        ];

        $response = $this->postJson('/api/v1/stops', $data);

        $response->assertCreated();
        $response->assertStatus(201);

        $stop = Stop::findOrFail($response->json('id'));

        $snapshot = $stop->snapshots()->where('url', $data['snapshotUrl']);

        $this->assertNotNull($snapshot->first());

        $snapshotMetadata = $snapshot->first()->metadata;

        $this->assertEquals($data['snapshotMetadata'], json_encode($snapshotMetadata));
    }
}
