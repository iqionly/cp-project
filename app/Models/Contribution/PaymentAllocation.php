<?php

namespace App\Models\Contribution;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'payment_id',
    'invoice_id',
    'amount',
])]
class PaymentAllocation extends Model
{
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
