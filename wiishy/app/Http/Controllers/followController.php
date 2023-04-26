<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userfollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class followController extends Controller
{
//_____________________ All the followers of a user 
    function user_followers($user_id){
        $followers=DB::table('users')
        ->join('userfollows','users.id','=','userfollows.user_id')
        ->where(['userfollows.follow_id'=>$user_id , 'follow_status'=>1])
        ->select('userfollows.user_id','userImageUrl' , 'name' , 'family')
        ->get();
        try{
            $followers_count=User::where('id' , $user_id)->first()->followers;
        }
        catch(\Exception $exception){
            return response(['message'=>'user not found'] , 400);
        }
        return response(['followers_count'=>$followers_count , 'followers'=>$followers]);
    }  
    
//_____________________ All the following of a user 
    function user_followings($user_id){
        $followings=DB::table('users')
        ->join('userfollows','users.id','=','userfollows.follow_id')
        ->where(['userfollows.user_id'=>$user_id , 'follow_status'=>1])
        ->select('follow_id as user_id','userImageUrl' , 'name' , 'family')
        ->get();
        try{
            $followings_count=User::where('id' , $user_id)->first()->followings;
        }
        catch(\Exception $exception){
                return response(['message'=>'user not found'] , 400);
        }
        
        return response(['followings_count'=>$followings_count , 'followings'=>$followings]);
    }

//_____________________ Follow Check
    function followcheck($user_id,$follow_id){
        $follow=userfollow::where(['user_id'=>$user_id , 'follow_id'=>$follow_id])->first();
        if(!$follow)
            return false;
        $unfollowed=userfollow::where(['user_id'=>$user_id,'follow_id'=>$follow_id])->latest()->first();
        if(!$unfollowed->follow_status)
            return false;
        return true;
    }

//_____________________ IS Follow?
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

//_____________________ UnFollow
    function unfollow($user_id,$follow_id){
        $follow=$this->followcheck($user_id,$follow_id);
        if(!$follow)
            return response(['message'=>'user hasnt been followed'],400);
         //update table
        userfollow::where(['user_id'=>$user_id , 'follow_id'=>$follow_id])->update(['follow_status'=>0]);
        //decrease followers & followings
        User::where('id',$user_id)->decrement('followings');
        User::where('id',$follow_id)->decrement('followers');
        return response(['message'=>'The Unfollow process has done successfully']);
    }
}
