<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

// #[Guarded(['id'])]
#[Fillable(['url', 'metadata', 'snapshotable_id', 'snapshotable_type'])]
class Snapshot extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return objec
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'object',
        ];
    }

    public function snapshotable(): MorphTo
    {
        return $this->morphTo();
    }
}
