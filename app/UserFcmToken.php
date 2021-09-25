<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFcmToken extends Model
{
    protected $table = 'user_fcm_tokens';

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
