<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPreferenceValue extends Pivot
{
    protected $guarded = [];

    public $incrementing = true;
}
