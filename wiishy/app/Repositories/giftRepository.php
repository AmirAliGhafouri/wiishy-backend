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
        ->select('giftuser.gift_id' , 'giftuser.gift_view' , 'giftuser.gift_like' , 'giftuser.desire_rate' , 'giftuser.created_at' , 'giftName' , 'giftPrice' , 'giftDesc' , 'giftUrl')
        ->get();
    }

    static function gift_details($gift_id , $user_id){
        return DB::table('giftuser')
        ->join('users','users.id','=','giftuser.user_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where(['giftuser.user_id'=>$user_id , 'giftuser.gift_id'=>$gift_id , 'users.status'=>1 , 'gift_status'=>1])
        ->select('user_id','gift_id','giftName','giftPrice','giftDesc','giftUrl','giftImageUrl','gift_like','gift_view','shared','desire_rate','giftuser.created_at','name','family','userImageUrl')
        ->get();
    }

    static function followings_gift($id){
        return DB::table('giftuser')
        ->join('users','giftuser.user_id','=','users.id')
        ->join('userfollows','giftuser.user_id','=','userfollows.follow_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where('userfollows.user_id',$id )
        ->where(['follow_status'=>1 , 'gift_status'=>1])
        ->select('giftuser.user_id','giftuser.gift_id','giftName','giftUrl','giftImageUrl','gift_like','giftuser.created_at as giftuser_created_at','name','family','userImageUrl')
        ->get()->sortByDesc('giftuser_created_at')->values();
    }

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

    static function update($gift_id, $req, $field){
        gift::where('id',$gift_id)->update([$field=>$req]);
    }
}