<?php
namespace App\Repositories;

use App\Models\giftlike;
use App\Models\giftUser;

class giftRepository
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
}