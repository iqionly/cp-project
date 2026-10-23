<?php

namespace App\Models\Contribution;

use App\Models\MasterData\Member;
use App\Models\Traits\FormatDate;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'member_id',
    'invoice_number',
    'period_start',
    'issued_at',
    'due_at',
    'status',
    'notes',
])]
class Invoice extends Model
{
    use FormatDate;

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'issued_at' => 'date',
            'due_at' => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function invoice_items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(
            PaymentAllocation::class
        );
    }

    protected function dueAt(): Attribute
    {
        return $this->makeAttribute('medium');
    }

    protected function periodStart(): Attribute
    {
        return $this->makeAttribute('medium');
    }

    public function getTotalAmountAttribute(): string
    {
        return (string) $this->invoice_items()
            ->sum('amount');
    }

    public function getPaidAmountAttribute(): string
    {
        return (string) $this->allocations()
            ->whereHas('payment', function ($query) {
                $query->where('status', 'APPROVED');
            })
            ->sum('amount');
    }

    public function getRemainingAmountAttribute(): string
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getPayedAttribute(): bool
    {
        return $this->remaining_amount == 0;
    }

    public function getPartiallyAttribute(): bool
    {
        return $this->remaining_amount > 0;
    }

    public function getNotYetAttribute(): bool
    {
        return $this->paid_amount == 0;
    }
}
