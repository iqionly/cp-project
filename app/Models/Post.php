<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(PostFactory::class)]
class Post extends Model
{
    use HasFactory;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
