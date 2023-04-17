<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userfollowingcount;
use App\Models\userFollwerCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class giftsController extends Controller
{
    
//_____________________ All the gifts of a user
    function user_gifts($user_id){
        $gifts=DB::table('giftuser')
        ->join('gifts','giftuser.gift_id','=','gifts.id')
        ->where(['giftuser.user_id'=>$user_id , 'status'=>1])
        ->select('giftuser.gift_id' , 'giftuser.gift_view' , 'giftuser.gift_like' , 'giftuser.desire_rate' , 'giftuser.created_at' , 'giftName' , 'giftPrice' , 'giftDesc' , 'giftUrl')
        ->get();
        /* if(!$gifts->all())
            return response(['message'=>'no gifts found'],500); */
        return response(['gifts'=>$gifts]);
    }

//_____________________ A complete gift detail of a user
    function gift_detail($gift_id , $user_id){
        $gift_detail=DB::table('giftuser')
        ->join('users','users.id','=','giftuser.user_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where(['giftuser.user_id'=>$user_id , 'giftuser.gift_id'=>$gift_id , 'users.status'=>1 , 'gifts.status'=>1])
        ->select('user_id','gift_id','giftName','giftPrice','giftDesc','giftUrl','giftImageUrl','gift_like','gift_view','shared','desire_rate','giftuser.created_at','name','family','userImageUrl')
        ->get();
        /* if(!$gift_detail->all())
            return response(['message'=>'Not found'] , 500); */
        return response(['gift_detail'=>$gift_detail]);
    }

//_____________________ All the gifts of the users followings
    function followings_gift($id){
        $gifts=DB::table('giftuser')
        ->join('users','giftuser.user_id','=','users.id')
        ->join('userfollowings','giftuser.user_id','=','userfollowings.following_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where('userfollowings.user_id',$id )
        ->select('giftuser.gift_id','giftName','giftUrl','giftImageUrl','gift_like','giftuser.created_at as giftuser_created_at','name','family','userImageUrl')
        ->get()->sortByDesc('giftuser_created_at')->values();
        $count=$gifts->count();
        return response(['followings_gifts_count'=>$count ,'followings_gifts'=>$gifts]);
    }
}
