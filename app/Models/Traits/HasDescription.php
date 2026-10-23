<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasDescription
{
    public function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => substr($this->getAttribute('description'), 0, 100).(strlen($this->getAttribute('description')) <= 100 ? null : '...'),
        );
    }

    public function mediumDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => substr($this->getAttribute('description'), 0, 200).(strlen($this->getAttribute('description')) <= 200 ? null : '...'),
        );
    }
}
