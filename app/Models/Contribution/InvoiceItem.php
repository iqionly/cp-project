<?php

namespace App\Models\Contribution;

use App\Models\Traits\HasDescription;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'invoice_journal_id',
    'description',
    'quantity',
    'unit_price',
    'amount',
])]
class InvoiceItem extends Model
{
    use HasDescription, HasTimestamps;

    public function invoice_journal(): BelongsTo
    {
        return $this->belongsTo(InvoiceJournal::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
