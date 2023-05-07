<?php
namespace App\Repositories;

use App\Models\giftlike;
use App\Models\giftUser;
use Illuminate\Support\Facades\DB;

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

    static function count($gift_id){
        return giftUser::where(['gift_id'=>$gift_id , 'gift_status'=>1])->first()->gift_like;
    }

    static function list($gift_id){
        DB::table('giftlike')
        ->join('users','giftlike.user_id','=','users.id')
        ->where('giftlike.gift_id',$gift_id)
        ->select('user_id','name','family','user_image_url','giftlike.created_at as like_date')
        ->get()->sortByDesc('like_date')->values();
    }
}