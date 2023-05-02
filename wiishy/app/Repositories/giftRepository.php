<?php
namespace App\Repositories;

use App\Models\gift;
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

    static function gift_create($req){
        return gift::create([
            'giftName'=>$req->g_name,
            'giftPrice'=>$req->g_price,
            'giftDesc'=>$req->g_desc,
            'giftUrl'=>$req->g_link,
            'giftImageUrl'=>$req->g_name
        ]);
    }

    static function giftuser_create($req,$id){
        giftUser::create([
            'user_id'=>$req->id,
            'gift_id'=>$id,
            'desire_rate'=>$req->g_rate
        ]);
    }
}