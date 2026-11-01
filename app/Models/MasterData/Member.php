<?php

namespace App\Models\MasterData;

use App\Models\Contribution\Invoice;
use App\Models\Contribution\MemberFeeSetting;
use App\Models\Contribution\Payment;
use App\Models\EventRegistration;
use App\Models\Traits\FormatDate;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Database\Factories\MasterData\MemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

#[Fillable([
    'member_code',
    'first_name',
    'last_name',
    'gender',
    'birth_date',
    'address',
    'nik',
    'plate_number',
    'phone_number',
    'emergency_person',
    'emergency_phone_number',
])]
#[UseFactory(MemberFactory::class)]
class Member extends Model
{
    use FormatDate, HasFactory, HasTimestamps, SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function feeSettings()
    {
        return $this->hasMany(MemberFeeSetting::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     */
    public function getFirstNameAttribute($value): ?string
    {
        return $value ? ucfirst($value) : null;
    }

    public function getLastNameAttribute($value): ?string
    {
        return $value ? ucfirst($value) : null;
    }

    public function getFullNameAttribute()
    {
        $fullName = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));

        return $fullName !== '' ? $fullName : ($this->user?->name ?? 'New Member');
    }

    public function gender(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? strtoupper($value) : null,
        );
    }

    public function maskedPhoneNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => ! empty($this->phone_number) ? Str::mask($this->phone_number, '*', 3, max(0, strlen($this->phone_number) - 5)) : '-',
        );
    }

    /**
     * Get all active (unpaid or partially paid) invoices for the member.
     *
     * @return Collection<int, Invoice>
     */
    public function getActiveInvoices(): Collection
    {
        $invoices = $this->relationLoaded('invoices')
            ? $this->invoices
            : $this->invoices()->with(['invoice_items', 'allocations.payment'])->get();

        return $invoices->filter(fn (Invoice $invoice) => (float) $invoice->remaining_amount > 0)->values();
    }

    /**
     * Check if the member has any active (unpaid) iuran.
     */
    public function hasActiveIuran(): bool
    {
        return $this->getActiveInvoices()->isNotEmpty();
    }

    /**
     * Get total remaining amount of all active iuran.
     */
    public function getTotalActiveIuranAmount(): float
    {
        return (float) $this->getActiveInvoices()->sum(fn (Invoice $inv) => (float) $inv->remaining_amount);
    }

    /**
     * Get the international standard WhatsApp phone number (e.g. 628...).
     */
    public function formattedWhatsAppNumber(): ?string
    {
        return WhatsAppNotificationService::formatPhoneNumber($this->phone_number);
    }

    /**
     * Check if the member has a valid WhatsApp phone number.
     */
    public function hasValidWhatsAppNumber(): bool
    {
        return $this->formattedWhatsAppNumber() !== null;
    }
}
