<?php

namespace App\Http\Controllers;

use App\Data\Stops\SummaryData;
use App\Services\Stops\StopService;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;

class StopController extends Controller
{
    public function __construct(protected StopService $service) {}

    /** @return DataCollection<int, SummaryData>|Collection<int, SummaryData> */
    public function index(): DataCollection|Collection
    {
        return SummaryData::collect($this->service->getAll());
    }
}
