<?php

namespace App\Models;

use App\Models\Traits\HasDescription;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[UseFactory(PostFactory::class)]
#[Fillable(
    'title',
    'description',
    'contents',
    'path_featured_image',
    'path_images',
    'reviewed_at',
    'published_at',
)]
class Post extends Model
{
    use HasFactory, HasTimestamps, SoftDeletes, HasDescription;

    protected function casts(): array
    {
        return [
            'path_images' => 'array',
        ];
    }

    public function isReviewed(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty($this->getAttribute('reviewed_at')),
        );
    }

    public function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn() => !empty($this->getAttribute('published_at')),
        );
    }

    public function pathFeaturedImage(): Attribute
    {
        return Attribute::make(
            get: fn($value) => filter_var($value, FILTER_VALIDATE_URL) ? $value : (!empty($value) ? url('storage/'.$value) : "https://placehold.co/200x100.png"),
        );
    }

    public function review(bool $y = true)
    {
        $this->reviewed_at = $y ? date('Y-m-d H:i:s') : null;
        return $this;
    }

    public function publish(bool $y = true)
    {
        $this->published_at = $y ? date('Y-m-d H:i:s') : null;
        return $this;
    }

    public function menu_public(bool $y = true)
    {
        $this->menu = $y;
        return $this;
    }

    public function slug(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? $value : Str::slug($this->getAttribute('title')),
            set: fn($value) => $value ? $value : Str::slug($this->getAttribute('title')),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
