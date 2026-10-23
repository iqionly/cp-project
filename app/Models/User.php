<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Authorizations\Role;
use App\Models\MasterData\Member;
use App\Models\Traits\FormatDate;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
#[UserFactory(UserFactory::class)]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use FormatDate, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function verify(bool $y = true)
    {
        $this->email_verified_at = $y ? date('Y-m-d H:i:s') : null;

        return $this;
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function maskedEmail(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->email ? Str::mask($this->email, '*', 4) : '-',
        );
    }

    public function isVerified(): Attribute
    {
        return Attribute::make(
            get: fn () => ! empty($this->email_verified_at),
        );
    }

    public function isProfileComplete(): bool
    {
        if (empty($this->email) || ! $this->member) {
            return false;
        }

        return ! empty($this->member->first_name)
            && ! empty($this->member->last_name)
            && ! empty($this->member->address)
            && ! empty($this->member->phone_number);
    }
}
