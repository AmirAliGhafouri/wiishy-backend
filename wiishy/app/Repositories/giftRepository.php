<?php
namespace App\Repositories;

use App\Models\gift;
use App\Models\giftUser;
use Illuminate\Support\Facades\DB;

class giftRepository
{
    static function user_gift($user_id){
        return DB::table('giftuser')
        ->join('gifts','giftuser.gift_id','=','gifts.id')
        ->where(['giftuser.user_id'=>$user_id , 'gift_status'=>1])
        ->select('giftuser.gift_id' , 'giftuser.gift_view' , 'giftuser.gift_like' , 'giftuser.desire_rate' , 'giftuser.created_at as giftuserCreated_at' , 'gift_name' , 'gift_price' , 'gift_desc' , 'gift_url')
        ->get();
    }

    static function gift_details($gift_id , $user_id){
        return DB::table('giftuser')
        ->join('users','users.id','=','giftuser.user_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where(['giftuser.user_id'=>$user_id , 'giftuser.gift_id'=>$gift_id , 'users.status'=>1 , 'gift_status'=>1])
        ->select('user_id','gift_id','gift_name','gift_price','gift_desc','gift_url','gift_image_url','gift_like','gift_view','shared','desire_rate','giftuser.created_at','name','family','user_image_url')
        ->get();
    }

    static function followings_gift($id){
        return DB::table('giftuser')
        ->join('users','giftuser.user_id','=','users.id')
        ->join('userfollows','giftuser.user_id','=','userfollows.follow_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where('userfollows.user_id',$id )
        ->where(['follow_status'=>1 , 'gift_status'=>1])
        ->select('giftuser.user_id','giftuser.gift_id','gift_name','gift_url','gift_image_url','gift_like','giftuser.created_at as giftuser_created_at','name','family','user_image_url')
        ->get()->sortByDesc('giftuser_created_at')->values();
    }

    static function create($req){
        return gift::create($req); 
    }


    static function update($gift_id, $request){
        gift::where('id',$gift_id)->update($request);
    }
}