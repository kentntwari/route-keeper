<?php

namespace App\Http\Controllers\Api\V1;

use App\Data\Resources\StopDetailsResource;
use App\Data\Resources\StopSummaryResource;
use App\Data\Stops\StopData;
use App\Exceptions\AvoidDuplicateStopException;
use App\Http\Controllers\Controller;
use App\Services\StopService;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;

class StopController extends Controller
{
    public function __construct(protected StopService $service) {}

    /** @return DataCollection<int, StopSummaryResource>|Collection<int, StopSummaryResource> */
    public function index(): DataCollection
    {
        return StopSummaryResource::collect($this->service->getAll(), DataCollection::class);
    }

    public function store(StopData $data)
    {

        try {
            $stop = $this->service->create($data);

            return response()->json($stop, 201);
        } catch (AvoidDuplicateStopException $e) {

            return response()->json(['error' => $e->getMessage()], 409);
        }

    }

    public function show(string|int $year, string|int|null $month = null, string|int|null $date = null)
    {

        if (! $month && ! $date) {
            $stops = $this->service->getByYear($year);

            return StopSummaryResource::collect($stops, DataCollection::class);
        } elseif (! $date) {
            $stops = $this->service->getByMonth($year, $month);

            return StopSummaryResource::collect($stops, DataCollection::class);
        } else {
            $stop = $this->service->getByDate($year, $month, $date);

            return StopDetailsResource::from($stop);
        }
    }

    public function destroyById(string $id)
    {
        $this->service->deleteById($id);

        return response()->noContent();
    }

    public function destroy(string|int $year, string|int|null $month = null, string|int|null $date = null)
    {
        try {
            if (! $month || ! $date) {
                throw new \InvalidArgumentException('Month and date must be provided for deletion requests.');
            }

            $this->service->deleteByDate($year, $month, $date);

            return response()->noContent();
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }
}
