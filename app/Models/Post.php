<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    use HasFactory;

    protected function casts(): array
    {
        return [
            'path_images' => 'array',
        ];
    }

    public function isReviewed(): Attribute
    {
        return new Attribute(
            get: fn() => !empty($this->getAttribute('reviewed_at')),
        );
    }

    public function isPublished(): Attribute
    {
        return new Attribute(
            get: fn() => !empty($this->getAttribute('published_at')),
        );
    }

    public function shortDescription(): Attribute
    {
        return new Attribute(
            get: fn() => substr($this->getAttribute('description'), 0, 100),
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
