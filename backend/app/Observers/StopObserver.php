<?php

namespace App\Observers;

use App\Models\Stop;

class StopObserver
{
    public function creating(Stop $stop): void
    {

        if ($stop->exception !== null) {
            $stop->start_time = '00:00';
            $stop->end_time = '00:00';
            $stop->total_time = null;
            $stop->estimated_time = null;
            $stop->deliveries_count = 0;
            $stop->pickups_count = 0;
        }

        if ($stop->total_time !== null && $stop->estimated_time !== null) {
            $stop->estimated_time = null;
        }
    }
}
