<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class giftUser extends Model
{
    use HasFactory;
    protected $table="giftuser";
    protected $fillable=['user_id','gift_id','desire_rate','gift_like','gift_view','shared','date_register'];

}
