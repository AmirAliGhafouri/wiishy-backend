<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userevent extends Model
{
    use HasFactory;
    protected $fillable=['user_id','name','family','gender','relationship','event_type','event_date'];
}
