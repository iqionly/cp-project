<?php

namespace App\Models\Contribution;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'code',
    'default_amount',
])]
class InvoiceJournal extends Model
{
    use HasTimestamps;
}
