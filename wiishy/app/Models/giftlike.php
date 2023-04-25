<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class giftlike extends Model
{
    use HasFactory;
    protected $table='giftlike';
    protected $fillable=['user_id','gift_id'];
}
