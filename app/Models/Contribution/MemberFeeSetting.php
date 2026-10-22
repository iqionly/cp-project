<?php

namespace App\Models\Contribution;

use App\Models\Traits\HasDescription;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberFeeSetting extends Model
{
    use HasTimestamps, HasDescription;
}
