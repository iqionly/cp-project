<?php

namespace App\Models\Authorizations;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'role_grouping', 'description'])]
class Role extends Model
{
    use HasFactory, HasTimestamps, SoftDeletes;
}
