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
        return User::create([
            'name'=>$req->name,
            'family'=>$req->family,
            'user_birthday'=>$req->user_birthday,
            'user_location_id'=>$req->user_location_id,
            'user_gender'=>$req->user_gender,
            'user_desc'=>$req->user_desc,
            'user_image_url'=>$req->user_image_url,
            'user_code'=>$req->user_code
        ]);
    }

    static function get($id){
        return User::where(['id'=>$id,'status'=>1])->first();
    }

    static function update($id, $req, $field){
        User::where('id',$id)->update([$field=>$req]);
    }
}