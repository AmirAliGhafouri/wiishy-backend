<?php
namespace App\Repositories;

use App\Models\giftlike;
use App\Models\giftUser;

class likeRepository
{
    static function check($gift_id , $user_id){
        $like=giftlike::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->first();
        if($like)
            return true;
        return false;
    }

    static function like($gift_id , $user_id){
        giftlike::create([
            'user_id'=>$user_id,
            'gift_id'=>$gift_id
        ]);
    }

    static function dislike($gift_id , $user_id){
        giftlike::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->delete();
    }

    static function increase($gift_id , $user_id){
        giftUser::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->increment('gift_like');
    }

    static function decrease($gift_id , $user_id){
        giftUser::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->decrement('gift_like');
    }
}