<?php

namespace App\Models;

use App\Models\Traits\HasDescription;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

#[Fillable([
    'event_id',
    'title',
    'description',
    'path_image',
    'taken_at',
    'tags',
    'privated_at',
])]
class Gallery extends Model
{
    use HasDescription, HasTimestamps;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    protected $file_size;

    protected $file_url;

    protected $file_pretty_size;

    protected $file_metadata;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('public', function (Builder $builder) {
            $builder->where('privated_at', null);
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
            'tags' => 'array',
            'taken_at' => 'date',
        ];
    }

    public function takenDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->taken_at ? $this->taken_at->format('d M Y') : $this->created_at?->format('d M Y'),
        );
    }

    public function pathImage(): Attribute
    {
        return Attribute::make(
            get: fn () => url($this->fileUrl()),
        );
    }

    public function fileSize()
    {
        if (! $this->getRawOriginal('path_image')) {
            return 0;
        }

        if ($this->file_size) {
            return $this->file_size;
        }

        return $this->file_size = Storage::disk('public')->size($this->getRawOriginal('path_image'));
    }

    public function filePrettySize()
    {
        if ($this->file_pretty_size) {
            return $this->file_pretty_size;
        }

        if (! $this->fileSize()) {
            return null;
        }

        return $this->file_pretty_size = Number::fileSize($this->file_size);
    }

    public function fileUrl()
    {
        if (! $this->getRawOriginal('path_image')) {
            return null;
        }

        if ($this->file_url) {
            return $this->file_url;
        }

        return $this->file_url = Storage::url($this->getRawOriginal('path_image'));
    }

    public function fileMetadata()
    {
        if (! $this->getRawOriginal('path_image')) {
            return null;
        }

        try {
            return $this->file_metadata = getimagesize(
                storage_path('app/public/'.$this->getRawOriginal('path_image'))
            );
        } catch (\ErrorException $e) {
            return null;
        }
    }

    public function fileDimensions()
    {
        if (! $this->fileMetadata()) {
            return null;
        }

        return $this->file_metadata[0].'x'.$this->file_metadata[1];
    }
}
