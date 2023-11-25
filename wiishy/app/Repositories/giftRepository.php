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
            ->select('*','gifts.id as gift_id','gifts.created_at as gifts_created_at')
            ->get()->sortByDesc('gifts_created_at')->values();
    }

    static function gift_details($gift_id ){
        return DB::table('gifts')
        ->join('users','users.id','=','gifts.user_id')
        ->where(['gifts.id'=>$gift_id , 'gift_status'=>1])
        ->select('*','gifts.created_at as gifts_created_at')
        ->get();
    }

    static function followings_gift($id){
        return DB::table('gifts')
        ->join('users','gifts.user_id','=','users.id')
        ->join('userfollows','gifts.user_id','=','userfollows.follow_id')
        ->where('userfollows.user_id',$id )
        ->where(['follow_status'=>1 , 'gift_status'=>1])
        ->select('*','gifts.id as gift_id','gifts.created_at as gifts_created_at')
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

    static function price_unit($id){
        $unit=price_unit::where('id',$id)->first();
        if(!$unit)
            return '?';
        return $unit->symbol; 
    }

    static function units(){
        return price_unit::all();
    }
    
}