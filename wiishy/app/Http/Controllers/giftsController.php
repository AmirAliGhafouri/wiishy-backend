<?php

namespace App\Http\Controllers;

use App\Models\gift;
use App\Models\giftUser;
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
        return response(['gift_detail'=>$gift_detail]);
    }

//_____________________ All the gifts of the users followings
    function followings_gift($id){
        $gifts=DB::table('giftuser')
        ->join('users','giftuser.user_id','=','users.id')
        ->join('userfollowings','giftuser.user_id','=','userfollowings.following_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where('userfollowings.user_id',$id )
        ->select('giftuser.user_id','giftuser.gift_id','giftName','giftUrl','giftImageUrl','gift_like','giftuser.created_at as giftuser_created_at','name','family','userImageUrl')
        ->get()->sortByDesc('giftuser_created_at')->values();
        $count=$gifts->count();
        return response(['followings_gifts_count'=>$count ,'followings_gifts'=>$gifts]);
    }

//_____________________ Add New Gift
    function add_gift(Request $req , $user_id){
        $req->validate([
            'g_name'=>'required | max:100',
            'g_price'=>'required | numeric | max:60',
            'g_desc'=>'required',
            'g_rate'=>'required | max:2',
            'g_image'=>'required | image'
        ]);

        //Save Image
        $file=$req->file('g_image');
        $filename=$file->getClientOriginalName();
        $dstPath=public_path()."/images/gifts";
        $file->move($dstPath,$filename);

        $gift=gift::create([
            'giftName'=>$req->g_name,
            'giftPrice'=>$req->g_price,
            'giftDesc'=>$req->g_desc,
            'giftUrl'=>$req->g_link,
            'status'=>1,
            'giftImageUrl'=>"http://wiishy-backend.ir/images/gifts/".$filename
        ]);

        giftUser::create([
            'user_id'=>$user_id,
            'gift_id'=>$gift->id,
            'desire_rate'=>$req->g_rate,
            'gift_like'=>0,
            'gift_view'=>0,
            'shared'=>0,
            'date_register'=>date('y,m,d')
        ]);

        return response(['message'=>"The gift has been successfully added"]);
    }
}
