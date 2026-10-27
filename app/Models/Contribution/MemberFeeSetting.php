<?php

namespace App\Models\Contribution;

use App\Models\Traits\HasDescription;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class MemberFeeSetting extends Model
{
    use HasDescription, HasTimestamps;
}
