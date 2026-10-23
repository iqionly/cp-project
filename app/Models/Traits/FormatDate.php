<?php

namespace App\Models\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait FormatDate
{
    private string $default_date_format = 'D, d F Y H:i:s';

    private string $medium_date_format = 'D, d F Y';

    private string $long_date_format = 'D, d-M-y H:i:s';

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->default_date_format);
    }

    private function makeAttribute($format = 'default')
    {
        return Attribute::make(
            get: fn (mixed $value) => date($this->{$format.'_date_format'}, strtotime($value)),
        );
    }

    protected function createdAt(): Attribute
    {
        return $this->makeAttribute();
    }

    protected function updatedAt(): Attribute
    {
        return $this->makeAttribute();
    }

    protected function deletedAt(): Attribute
    {
        return $this->makeAttribute();
    }
}
