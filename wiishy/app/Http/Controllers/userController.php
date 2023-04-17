<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userfollowingcount;
use App\Models\userFollwerCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{
    //_____________________ All the followers of a user ??
    function user_followers($user_id){
        $followers=DB::table('users')
        ->join('userfollowers','users.id','=','userfollowers.follower_id')
        ->where(['userfollowers.user_id'=>$user_id , 'follower_status'=>1])
        ->select('follower_id','userImageUrl' , 'name' , 'family')
        ->get();
        try{
            $followers_counte=userFollwerCount::where('user_id' , $user_id)->first()->followers;
        }
        catch(\Exception $exception){
            return response(['message'=>'user not found'] , 500);
        }
        return response(['followers_count'=>$followers_counte , 'followers'=>$followers]);
    }
//_____________________ All the following of a user ??
    function user_followings($user_id){
        $followings=DB::table('users')
        ->join('userfollowings','users.id','=','userfollowings.following_id')
        ->where(['userfollowings.user_id'=>$user_id , 'following_status'=>1])
        ->select('following_id','userImageUrl' , 'name' , 'family')
        ->get();
        try{
            $followings_counte=userfollowingcount::where('user_id' , $user_id)->first()->followings;
        }
        catch(\Exception $exception){
                return response(['message'=>'user not found'] , 500);
        }
        
        return response(['followings_count'=>$followings_counte , 'followings'=>$followings]);
    }
//_____________________ User Profile
    function user_profile($user_id){
       /*  try{
            $user=User::findOrFail($user_id);
        }
        catch(\Exception $exception){
            return response(['message'=>'user not found'] , 500);
        } */
        $user=User::where(['id'=>$user_id,'status'=>1])->first();
        return response(['user'=>$user]);
    }

     /*  function adduser(Request $req){
        $user= new user;
        $user->name=$req->name;
        $user->family=$req->family;
        $user->userBirthday=$req->birth;
        $user->userLocationid=$req->loc;
        $user->userGender=$req->gender;
        $user->userDescription=$req->desc;
        $user->userImageUrl=$req->url;
        $user->status=$req->status;
        $user->userCode=$req->code;
        $user->save();
        return response(['message'=>'done'],200);
    } */
}
