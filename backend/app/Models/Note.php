<?php

namespace App\Models;

use Database\Factories\NoteFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Guarded(['id'])]
class Note extends Model
{
    /** @use HasFactory<NoteFactory> */
    use HasFactory;

    use HasUuids;

    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }
}
