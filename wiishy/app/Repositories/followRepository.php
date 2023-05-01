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

    static function list($user_id,$join,$field){
        return DB::table('users')
        ->join('userfollows','users.id','=',$join)
        ->where([$field=>$user_id , 'follow_status'=>1])
        ->select("$join as user_id",'userImageUrl' , 'name' , 'family' , 'status as user_status')
        ->get();
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
        userfollow::create([
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
//__________________________________________________
    static function all($user_id){
        return User::where(['id'=>$user_id,'status'=>1])->first();
    }

    static function destroy($user_id){
        return User::where(['id'=>$user_id,'status'=>1])->update(['status'=>0]);
    }

    

    static function get($id){
        return User::where(['id'=>$id,'status'=>1])->first();
    }

    static function update($id, $req, $field){
        User::where('id',$id)->update([$field=>$req]);
    }
}