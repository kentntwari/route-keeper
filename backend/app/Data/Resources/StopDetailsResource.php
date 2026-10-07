<?php

namespace App\Data\Resources;

use App\Models\Stop;

class StopDetailsResource extends StopSummaryResource
{
    public function __construct(
        public string $start_time,
        public ?string $end_time,
        public int $deliveries_count,
        public int $pickups_count,
        public ?string $total_time,
        public ?string $estimated_time,
        /** @var array<int,string> List of snapshot URLs */
        public array $snapshotUrls,
        /** @var array<int,string> List of note ids */
        public array $notesIds,
        /** @var array<int,string> List of incident ids */
        public array $incidentsIds,
        public string $created_at,
    ) {
        parent::__construct(
            $deliveries_count,
            $pickups_count,
            $total_time,
            $estimated_time,
            $created_at
        );
    }

    public static function fromModel(Stop $model): self
    {
        return new self(
            $model->start_time,
            $model->end_time,
            $model->deliveries_count,
            $model->pickups_count,
            $model->total_time,
            $model->estimated_time,
            $model->snapshots->pluck('url')->toArray(),
            $model->notes->pluck('id')->toArray(),
            $model->incidents->pluck('incident_id')->toArray(),
            $model->getAttribute('created_at'),
        );
    }
}
