<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{
//_____________________ User Profile
    function user_profile($user_id){
        $user=User::where(['id'=>$user_id,'status'=>1])->first();
        return response(['user'=>$user]);
    }

}
