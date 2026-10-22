<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;

trait HasDescription
{
    use HasAttributes;

    public function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn() => substr($this->getAttribute('description'), 0, 100),
        );
    }
}
