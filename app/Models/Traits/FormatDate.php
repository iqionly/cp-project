<?php

namespace App\Models\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

trait FormatDate
{
    private string $date_format = 'D, d F Y H:i:s';

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->date_format);
    }

    private function makeAttribute()
    {
        return Attribute::make(
            get: fn (mixed $value) => date($this->date_format, strtotime($value)),
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
