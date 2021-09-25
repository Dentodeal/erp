<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $casts = [
        'created_at' => 'datetime:M d Y',
        'updated_at' => 'datetime:M d Y',
    ];

    public function role_user()
    {
        return $this->belongsToMany('App\User','user_roles','role_id','user_id');
    }

    public function notification_types()
    {
        return $this->belongsToMany('App\NotificationType', 'role_has_notification_types','role_id', 'notification_type_id');
    }
}
