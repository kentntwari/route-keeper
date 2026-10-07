<?php

namespace App\Models;

use Database\Factories\IncidentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Incident extends Model
{
    /** @use HasFactory<IncidentFactory> */
    use HasFactory;

    use HasUuids;

    protected $primaryKey = 'incident_id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function booted(): void
    {
        static::saving(function ($incident) {
            if ($incident->incident_type !== null) {
                $incident->incident_type = strtolower(
                    str_replace(' ', '', trim($incident->incident_type))
                );
            }
        });
    }

    public function uniqueIds(): array
    {
        return ['id', 'incident_id'];
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
