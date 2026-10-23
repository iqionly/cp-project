<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'menu_title',
    'icons',
    'slug',
    'default',
])]
class MenuPost extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'post_id';

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
