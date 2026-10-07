<?php

namespace App\Data\Resources;

use Spatie\LaravelData\Resource;

class StopSummaryResource extends Resource
{
    public function __construct(
        public int $deliveries_count,
        public int $pickups_count,
        public ?string $total_time,
        public ?string $estimated_time,
        public string $created_at,
    ) {}
}
