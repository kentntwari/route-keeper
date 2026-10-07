<?php

namespace App\Services;

use App\Data\Stops\StopData;
use App\Enums\StopException;
use App\Exceptions\AvoidDuplicateStopException;
use App\Models\Stop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StopService
{
    /** @return Collection<int, Stop> */
    public function getAll(): Collection
    {
        return Stop::all();
    }

    public function getByYear(string|int $year): Collection
    {
        $stops = Stop::whereYear('created_at', $year)->get();

        return $stops;
    }

    public function getByMonth(string|int $year, string|int $month): Collection
    {
        $stops = Stop::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        return $stops;
    }

    public function getByDate(string|int $year, string|int $month, string|int $date): Stop
    {
        $stop = Stop::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereDay('created_at', $date)
            ->firstOrFail();

        if ($stop instanceof Stop) {
            $stop->load(['snapshots', 'notes']);
        }

        return $stop;
    }

    public function create(StopData $stopData): Stop
    {

        $isStopCreatedToday = Stop::whereDate('created_at', Carbon::today())->exists();

        if ($isStopCreatedToday) {
            throw new AvoidDuplicateStopException;
        }

        return DB::transaction(function () use ($stopData) {
            $stop = Stop::create([
                'start_time' => $stopData->startTime,
                'end_time' => $stopData->endTime,
                'deliveries_count' => $stopData->deliveriesCount,
                'pickups_count' => $stopData->pickupsCount,
                'total_time' => $stopData->getTotalTime(),
                'estimated_time' => $stopData->getEstimatedTime(),
                'exception' => $stopData->exception,
            ]);

            if (! $stopData->snapshotUrl) {
                return $stop;
            }

            $stop->snapshots()->create([
                'url' => $stopData->snapshotUrl,
                'metadata' => $stopData->snapshotMetadata,
            ]);

            return $stop;
        }, attempts: 3);
    }

    public function grantException(StopData $stop, StopException $exception): Stop
    {
        $stopModel = Stop::findOrFail($stop->id);

        if (isset($stopModel->exception) && $stopModel->exception === $exception) {
            return $stopModel;
        }

        $stopModel->exception = $exception;
        $stopModel->save();

        return $stopModel;
    }

    public function deleteById(string $id): bool
    {
        $stop = Stop::findOrFail($id);

        return $stop->delete();
    }

    public function deleteByDate(string|int $year, string|int $month, string|int $date): bool
    {

        // FIX: This should be a soft-delete
        $stop = Stop::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereDay('created_at', $date);

        return $stop->delete();
    }
}
