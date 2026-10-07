<?php

namespace App\Models\MasterData;

use App\Models\Traits\FormatDate;
use App\Models\Traits\SerializeDate;
use App\Models\User;
use Database\Factories\MasterData\MemberFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

#[Fillable([
    'first_name',
    'last_name',
    'gender',
    'birth_date',
    'address',
    'nik'.
    'plate_number',
    'phone_number',
    'emergency_person',
    'emergency_phone_number'
])]
#[UseFactory(MemberFactory::class)]
class Member extends Model
{
    use HasTimestamps, SoftDeletes, HasFactory, FormatDate;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function gender(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtoupper($value),
        );
    }
}
