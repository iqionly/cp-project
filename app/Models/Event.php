<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(
    'location',
    'start_at',
    'end_at',
    'max_attendant',
    'min_attendant'
)]
class Event extends Model
{
    use HasTimestamps, HasFactory;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function eventPeriod(): Attribute
    {
        return new Attribute(
            get: fn() => $this->start_at . ' to ' . $this->end_at,
        );
    }

    public function shortEventPeriod(): Attribute
    {
        return Attribute::make(
            get: fn() => substr($this->start_at, 0, -3) . ' to ' . substr($this->end_at, 0, -3),
        );
    }
}
