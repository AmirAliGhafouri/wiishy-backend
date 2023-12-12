<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gift extends Model
{
    use HasFactory;
    protected $fillable=['gift_name','gift_price','gift_desc','gift_url','gift_image_url','user_id','desire_rate','gift_like','gift_view','shared','my_product'];
}
