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
    function add_user(Request $req){
        $req->validate([
            'user_name'=>'required | max:60',
            'user_family'=>'required | max:60',
            'user_birthday'=>'required | date',
            'user_location'=>'required | integer | max:11',
            'user_gender'=>'required | integer | max:1',
            'user_description'=>'required',
            'user_code'=>'required'
        ]);
        $add=User::create([
            'name'=>$req->user_name,
            'family'=>$req->user_family,
            'userBirthday'=>$req->user_birthday,
            'userLocationid'=>$req->user_location,
            'userGender'=>$req->user_gender,
            'userDescription'=>$req->user_description,
            'userImageUrl'=>$req->user_image,
            'userCode'=>$req->user_code
        ]);
        /* if(!$add)
            return response(['message'=>'Fail to add user'],400); */
        return response(['message'=>'User has added successfully']);
    }

//_____________________ Remove Profile
    function remove($user_id){
        $user=User::where(['id'=>$user_id,'status'=>1])->update(['status'=>0]);
        if(!$user)
            return response(['message'=>'User not found'],400);
        return response(['message'=>'User has removed successfully']);
    }

}
