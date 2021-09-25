<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens,HasRoles,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:M d Y',
        'updated_at' => 'datetime:M d Y',
    ];

    public function preferences()
    {
      return $this->belongsToMany(
        'App\Preference',
        'user_preference_value',
        'user_id',
        'preference_id'
        )->using('App\UserPreferenceValue')->withPivot('value')->withTimestamps();
    }

    public function user_role()
    {
        return $this->belongsToMany('App\Role','user_roles','user_id','role_id');
    }

    public function saleOrers()
    {
        return $this->hasMany('App\SaleOrder','representative_id');
    }

    public function createdSaleOrders()
    {
        return $this->hasMany('App\SaleOrder','created_by_id');
    }

    public function createdPurchases()
    {
        return $this->hasMany('App\Purchase','created_by_id');
    }

    public function hasRecords()
    {
        if($this->saleOrers()->count() > 0 || $this->createdSaleOrders()->count() > 0 || $this->createdPurchases()->count() > 0) {
            return true;
        }  
        return false;
    }

    public static function representatives()
    {
        return self::select('users.*')
        ->join('user_roles','user_roles.user_id','=','users.id')
        ->join('roles','roles.id','=','user_roles.role_id')
        ->where('roles.name','=','Sales')
        ->orderBy('name','ASC')->get();
    }

    public function notification_types() {
        return $this->morphToMany(NotificationType::class, 'model', 'model_has_notification_types', 'model_id', 'notification_type_id');
    }

    public function fcm_tokens() {
        return $this->hasMany(UserFcmToken::class, 'user_id');
    }
}
