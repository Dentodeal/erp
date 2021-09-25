<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $guarded = [];

    public function users()
    {
      return $this->belongsToMany(
        'App\User',
        'user_preference_value',
        'preference_id',
        'user_id'
        )->using('App\UserPreferenceValue')->withPivot('value')->withTimestamps();
    }
}
