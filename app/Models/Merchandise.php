<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(
    'name',
    'description',
    'price',
    'stock',
    'path_image',
    'is_available',
)]
class Merchandise extends Model
{
    use HasFactory, HasTimestamps;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    public function pathImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getRawOriginal('path_image')
                ? url(Storage::url($this->getRawOriginal('path_image')))
                : null,
        );
    }

    /**
     * Scope a query to only include available merchandise.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }
}
