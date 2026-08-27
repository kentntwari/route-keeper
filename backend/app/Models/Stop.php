<?php

namespace App\Models;

use App\Enums\StopException;
use Database\Factories\StopFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
class Stop extends Model
{
    /** @use HasFactory<StopFactory> */
    use HasFactory;

    use HasUuids;

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

    /** @return MorphMany<Note, $this> */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
