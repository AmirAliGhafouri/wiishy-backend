<?php
namespace App\Repositories;

use App\Models\giftlike;

class giftRepository
{
    static function like_check($gift_id , $user_id){
        $like=giftlike::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->first();
        if($like)
            return true;
        return false;
    }
}