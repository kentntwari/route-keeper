<?php

namespace App\Models;

use Database\Factories\DoorCodeFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class DoorCode extends Model
{
    /** @use HasFactory<DoorCodeFactory> */
    use HasFactory;

    use HasUuids;

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
