<?php

namespace App\Data\Stops;

use App\Enums\StopException;
use Database\Factories\StopFactory;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class StopData extends Data
{
    public function __construct(
        public string $id,
        #[Regex('/^(?:[01]\d|2[0-3]):[0-5]\d$/')]
        public string $startTime,

        #[Nullable]
        #[Regex('/^(?:[01]\d|2[0-3]):[0-5]\d$/')]
        public Optional|null|string $endTime,

        #[IntegerType()]
        #[Min(0)]
        public int $deliveriesCount,

        #[IntegerType()]
        #[Min(0)]
        public int $pickupsCount,

        #[Url()]
        public string $snapshotUrl,

        #[Nullable]
        #[ArrayType]
        public ?array $snapshotMetadata,

        #[Nullable]
        #[Regex('/^(?:[01]\d|2[0-3]):[0-5]\d$/')]
        public ?string $totalTime,

        #[Nullable]
        #[Regex('/^(?:[01]\d|2[0-3]):[0-5]\d$/')]
        public ?string $estimatedTime,

        #[Nullable]
        #[Min(100)]
        #[WithCast(EnumCast::class, type: StopException::class)]
        public ?StopException $exception,

    ) {}

    public function getTotalTime(): ?string
    {

        if ($this->totalTime) {
            return $this->totalTime;
        }

        $start = \DateTime::createFromFormat('H:i', $this->startTime);
        $end = \DateTime::createFromFormat('H:i', $this->endTime);
        if ($start && $end) {
            $interval = $start->diff($end);

            return $interval->format('%H:%I');
        }

        return null;
    }

    public function getEstimatedTime(): ?string
    {

        if ($this->estimatedTime) {
            return $this->estimatedTime;
        }

        $totalTime = $this->getTotalTime();

        if (! $totalTime) {
            return null;
        }

        return StopFactory::assignEstimatedTime(
            (int) \DateTime::createFromFormat('H:i', $totalTime)->format('H') * 60 +
            (int) \DateTime::createFromFormat('H:i', $totalTime)->format('i'));
    }
}
