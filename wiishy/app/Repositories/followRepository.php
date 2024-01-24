<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\userfollow;
use Illuminate\Support\Facades\DB;

class followRepository
{
    static function count($user_id,$item){
        return User::where('id' , $user_id)->first()->$item;
    }

    static function follow_list($user_id,$join,$field){
        return DB::table('users')
        ->join('userfollows','users.id','=',$join)
        ->where([$field=>$user_id , 'follow_status'=>1])
        ->select('*',"$join as user_id", 'status as user_status' , "$field as id")
        ->get();
    }

    static function list($user_id,$join,$field,$my_id){
        return DB::table('users')
        ->join('userfollows','users.id','=',$join)
        ->where([$field=>$user_id , 'follow_status'=>1])
        ->where($join, '!=' , $my_id)
        ->select('*',"$join as user_id", 'status as user_status' , "$field as id")
        ->get();
    }

    static function suggestions($followings,$my_id){
        $follow_suggestions=array();
        $suggestions_userid=array();
        foreach($followings as $user){
            $follower_list=followRepository::list($user->user_id,'userfollows.user_id','userfollows.follow_id',$my_id);
            array_push($follow_suggestions,$follower_list);
        }
        foreach($follow_suggestions as $users){
            foreach($users as $user){
                array_push($suggestions_userid,$user->user_id);
            }
        }
        return $suggestions_userid;
    } 

    static function unique($fooloweings,$followers){
        $suggestions=array_merge($fooloweings,$followers);
        return array_unique($suggestions);
    }

    static function filter($suggestions,$my_id){
        $users=array();
        foreach($suggestions as $item){
            $check=followRepository::check($my_id,$item);
            if(!$check)
                array_push($users,$item);
       }
       return $users;
    }

    static function follow_suggestion($users){
        $user_details=array();
        foreach($users as $user){
            $details=userRepository::all($user);
            array_push($user_details,$details);
        }
        return $user_details;
    }

    static function check($user_id,$follow_id){
        $follow=userfollow::where(['user_id'=>$user_id , 'follow_id'=>$follow_id])->first();
        if(!$follow)
            return false;
        $unfollowed=userfollow::where(['user_id'=>$user_id,'follow_id'=>$follow_id])->latest()->first();
        if(!$unfollowed->follow_status)
            return false;
        return true;
    }

    static function follow($user_id,$follow_id){
        return userfollow::create([
            'user_id'=>$user_id,
            'follow_id'=>$follow_id
        ]);
    }

    static function unfollow($user_id,$follow_id){
        userfollow::where(['user_id'=>$user_id , 'follow_id'=>$follow_id])->update(['follow_status'=>0]);
    }

    static function increase($id , $field){
        User::where('id',$id)->increment($field);
    }

    static function decrease($id , $field){
        User::where('id',$id)->decrement($field);
    }
}