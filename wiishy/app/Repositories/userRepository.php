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
            'name'=>$req->user_name,
            'family'=>$req->user_family,
            'userBirthday'=>$req->user_birthday,
            'userLocationid'=>$req->user_location,
            'userGender'=>$req->user_gender,
            'userDescription'=>$req->user_description,
            'userImageUrl'=>$req->user_image,
            'userCode'=>$req->user_code
        ]);
    }

    static function get($id){
        return User::where(['id'=>$id,'status'=>1])->first();
    }

    static function update($id, $req, $field){
        User::where('id',$id)->update([$field=>$req]);
    }
}