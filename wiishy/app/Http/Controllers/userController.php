<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userfollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{
//_____________________ User Profile
    function user_profile($user_id){
        $user=User::where(['id'=>$user_id,'status'=>1])->first();
        return response(['user'=>$user]);
    }

//_____________________ User Profile
    function remove($user_id){
        $user=User::where(['id'=>$user_id,'status'=>1])->update(['status'=>0]);
        if(!$user)
            return response(['message'=>'User not found'],400);
        return response(['message'=>'User has removed successfully']);
    }

}
