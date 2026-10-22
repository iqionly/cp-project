<?php

namespace App\Models\Contribution;

use App\Models\MasterData\Member;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'member_id',
        'payment_number',
        'paid_at',
        'amount',
        'method',
        'reference_number',
        'status',
        'notes',
        'evidence_path',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function review(bool $y = true)
    {
        $this->status = $y ? "REVIEWED" : "REJECTED";
        return $this;
    }

    public function approve(bool $y = true)
    {
        $this->status = $y ? "APPROVED" : "REJECTED";
        return $this;
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class);
    }
}
