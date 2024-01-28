<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'family',
        'user_birthday',
        'user_location_id',
        'user_gender',
        'user_desc',
        'user_image_url',
        'user_code',
        'provider_id',
        'producer',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizables');
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    /*
    public function followingGifts()
    {
        return $this->hasManyThrough(Gift::class, userfollow::class, 'user_id', 'user_id', 'id', 'follow_id')
            ->where(['userfollows.follow_status' => 1, 'gifts.gift_status' => 1]);;
    } */

    public function followers()
    {
        return $this->belongsToMany(User::class, 'userfollows', 'follow_id', 'user_id')->wherePivot('follow_status', 1);
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'userfollows', 'user_id', 'follow_id')->wherePivot('follow_status', 1);
    }
}
