<?php
namespace App\Repositories;

use App\Models\User;

class userRepository
{
    static function all($user_id){
        return User::where(['id'=>$user_id,'status'=>1])->first();
    }

    static function destroy($user_id){
        return User::where(['id'=>$user_id,'status'=>1])->update(['status'=>0]);
    }

    static function create($req){
        return User::create($req);
    }

    static function get($id){
        return User::where(['id'=>$id,'status'=>1])->first();
    }

    static function update($id,$request){
        User::where('id',$id)->update($request);
    }
}