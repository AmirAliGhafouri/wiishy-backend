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

//_____________________ Follow Check
    function followcheck($user_id,$follow_id){
        return userfollow::where(['user_id'=>$user_id , 'follow_id'=>$follow_id])->first();
    }

//_____________________ Follow
    function isfollow($user_id,$follow_id){
        $follow=$this->followcheck($user_id,$follow_id);
        if($follow)
            return response(['message'=>'yes']);
        return response(['message'=>'no']);
    }


//_____________________ Follow
    function follow($user_id,$follow_id){
        $follow=$this->followcheck($user_id,$follow_id);
        if($follow)
            return response(['message'=>'user has been already followed'],400);
        //Insert into table
        userfollow::create([
            'user_id'=>$user_id,
            'follow_id'=>$follow_id
        ]);
        //increase followers & followings
        User::where('id',$user_id)->increment('followings');
        User::where('id',$follow_id)->increment('followers');
        return response(['message'=>'The follow process has done successfully']);

    }

}
