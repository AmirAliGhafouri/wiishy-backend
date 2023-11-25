<?php
namespace App\Repositories;

use App\Models\gift;
use App\Models\price_unit;
use Illuminate\Support\Facades\DB;

class giftRepository
{
    static function all($user_id){
        return gift::where(['user_id'=>$user_id , 'gift_status'=>1])->get()->sortByDesc('created_at');
    }

    static function list($user_id){
        return DB::table('gifts')
            ->join('users','users.id','=','gifts.user_id')
            ->where('user_id','!=',$user_id)
            ->where('gift_status',1)
            ->select('gifts.id as gift_id','gift_name','gift_price','gift_desc','gift_url','gift_image_url','gift_like','gift_view','shared','desire_rate','gifts.created_at as gifts_created_at','user_id','name','family','user_image_url','user_birthday')
            ->get()->sortByDesc('gifts_created_at')->values();
    }

    static function gift_details($gift_id ){
        return DB::table('gifts')
        ->join('users','users.id','=','gifts.user_id')
        ->where(['gifts.id'=>$gift_id , 'gift_status'=>1])
        ->select('user_id','gifts.id','gift_name','gift_price','gift_desc','gift_url','gift_image_url','gift_like','gift_view','shared','desire_rate','gifts.created_at','name','family','user_image_url','user_birthday')
        ->get();
    }

    static function followings_gift($id){
        return DB::table('gifts')
        ->join('users','gifts.user_id','=','users.id')
        ->join('userfollows','gifts.user_id','=','userfollows.follow_id')
        ->where('userfollows.user_id',$id )
        ->where(['follow_status'=>1 , 'gift_status'=>1])
        ->select('gifts.user_id','gifts.id','gift_name','gift_url','gift_image_url','gift_like','gifts.created_at as gifts_created_at','name','family','user_image_url','user_birthday','gift_price')
        ->get()->sortByDesc('gifts_created_at')->values();
    }

    static function increase($gift_id , $field){
        gift::where('id',$gift_id)->increment($field);
    }

    static function get($gift_id , $user_id){
        return gift::where(['id'=>$gift_id ,'user_id'=>$user_id ,'gift_status'=>1])->first();
    }

    static function destroy($gift_id , $user_id){
        gift::where(['id'=>$gift_id , 'user_id'=>$user_id , 'gift_status'=>1])->update(['gift_status'=>0]);

    }

    static function create($req){
        return gift::create($req); 
    }

    static function update($gift_id, $request){
        gift::where('id',$gift_id)->update($request);
    }

    static function search($request){
        return gift::where('gift_name','like','%'.$request.'%')->get();
    }

    static function units(){
        return price_unit::all();
    }
    
}