<?php
namespace App\Repositories;

use App\Models\User;
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