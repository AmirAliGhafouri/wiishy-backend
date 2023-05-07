<?php
namespace App\Repositories;

use App\Models\giftUser;

class giftUserRepository
{
    static function increase($gift_id , $field){
        giftUser::where('gift_id',$gift_id)->increment($field);
    }

    static function get($gift_id , $user_id){
        return giftUser::where(['gift_id'=>$gift_id , 'user_id'=>$user_id , 'gift_status'=>1])->first();
    }

    static function destroy($gift_id , $user_id){
        giftUser::where(['gift_id'=>$gift_id , 'user_id'=>$user_id , 'gift_status'=>1])->update(['gift_status'=>0]);

    }

    static function create($req,$id){
        giftUser::create([
            'user_id'=>$req->id,
            'gift_id'=>$id,
            'desire_rate'=>$req->desire_rate
        ]);
    }

    static function update($gift_id, $req, $field){
        giftUser::where('id',$gift_id)->update([$field=>$req]);
    }
}