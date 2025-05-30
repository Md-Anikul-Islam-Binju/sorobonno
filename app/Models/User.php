<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    protected $guarded = ['id'];
//    protected $fillable = [
//        'name',
//        'email',
//        'phone',
//        'verification_code',
//        'password',
//        'device_ip',
//        'is_registration_by',
//        'email_verified_at',
//        'verification_code',
//        'status',
//        'google_id',
//        'avatar',
//    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        //'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function joinCategory()
    {
        return $this->belongsTo(JoinCategory::class,'join_category_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function couponCode()
    {
        return $this->hasMany(Coupon::class, 'agent_admin_id');
    }

}
