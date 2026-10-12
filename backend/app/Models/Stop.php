<?php

namespace App\Models;

use App\Enums\StopException;
use App\Observers\StopObserver;
use Database\Factories\StopFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'start_time',
    'end_time',
    'total_time',
    'estimated_time',
    'deliveries_count',
    'pickups_count',
    'exception',
])]
#[ObservedBy([StopObserver::class])]
class Stop extends Model
{
    /** @use HasFactory<StopFactory> */
    use HasFactory;

    use HasUuids;

    protected static function booted(): void
    {
        // FIX: Adapt for soft-deletion
        static::deleting(function (Stop $stop) {
            if ($stop->isSoftDeletable()) {
                $stop->notes()->delete();
                $stop->snapshots()->delete();
            } else {
                $stop->notes()->forceDelete();
                $stop->snapshots()->forceDelete();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'exception' => StopException::class,
        ];
    }

    public function incidents(): BelongsToMany
    {
        return $this->belongsToMany(
            Incident::class,
            'incident_stop',
            'stop_id',
            'incident_id'
        );
    }

    /** @return MorphMany<Note, $this> */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /** @return MorphMany<Snapshot, $this> */
    public function snapshots(): MorphMany
    {
        return $this->morphMany(Snapshot::class, 'snapshotable');
    }
}
