<?php

namespace App\Http\Controllers;

use App\Models\gift;
use App\Models\giftlike;
use App\Models\giftUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class giftsController extends Controller
{
    
//_____________________ All the gifts of a user
    function user_gifts($user_id){
        $gifts=DB::table('giftuser')
        ->join('gifts','giftuser.gift_id','=','gifts.id')
        ->where(['giftuser.user_id'=>$user_id , 'gift_status'=>1])
        ->select('giftuser.gift_id' , 'giftuser.gift_view' , 'giftuser.gift_like' , 'giftuser.desire_rate' , 'giftuser.created_at' , 'giftName' , 'giftPrice' , 'giftDesc' , 'giftUrl')
        ->get();
        return response(['gifts'=>$gifts]);
    }

//_____________________ A complete gift detail of a user
    function gift_detail($gift_id , $user_id){
        $gift_detail=DB::table('giftuser')
        ->join('users','users.id','=','giftuser.user_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where(['giftuser.user_id'=>$user_id , 'giftuser.gift_id'=>$gift_id , 'users.status'=>1 , 'gift_status'=>1])
        ->select('user_id','gift_id','giftName','giftPrice','giftDesc','giftUrl','giftImageUrl','gift_like','gift_view','shared','desire_rate','giftuser.created_at','name','family','userImageUrl')
        ->get();
        return response(['gift_detail'=>$gift_detail]);
    }

//_____________________ All the gifts of the users followings
    function followings_gift($id){
        $gifts=DB::table('giftuser')
        ->join('users','giftuser.user_id','=','users.id')
        ->join('userfollows','giftuser.user_id','=','userfollows.follow_id')
        ->join('gifts','gifts.id','=','giftuser.gift_id')
        ->where('userfollows.user_id',$id )
        ->where(['follow_status'=>1 , 'gift_status'=>1])
        ->select('giftuser.user_id','giftuser.gift_id','giftName','giftUrl','giftImageUrl','gift_like','giftuser.created_at as giftuser_created_at','name','family','userImageUrl')
        ->get()->sortByDesc('giftuser_created_at')->values();
        $count=$gifts->count();
        return response(['followings_gifts_count'=>$count ,'followings_gifts'=>$gifts]);
    }

//_____________________ Add New Gift
    function add_gift(Request $req , $user_id){
        $req->validate([
            'g_name'=>'required | max:100',
            'g_price'=>'required | numeric',
            'g_desc'=>'required',
            'g_rate'=>'required',
            'g_image'=>'required'
        ]);

        //insert into gifts
        $gift=gift::create([
            'giftName'=>$req->g_name,
            'giftPrice'=>$req->g_price,
            'giftDesc'=>$req->g_desc,
            'giftUrl'=>$req->g_link,
            'giftImageUrl'=>$req->g_name
        ]);

        //insert into giftuser
        $giftUser=giftUser::create([
            'user_id'=>$user_id,
            'gift_id'=>$gift->id,
            'desire_rate'=>$req->g_rate
        ]);
        if(!$gift || !$giftUser)
            return response(['message'=>"Fail to add gift"],400);
        return response(['message'=>"The gift has been successfully added"]);
    }

//_____________________ Like Check
    function like_check($gift_id , $user_id){
        $like=giftlike::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->first();
        if($like)
            return true;
        return false;
    }


//_____________________ Like a gift
    function like($gift_id , $user_id){
        $like=$this->like_check($gift_id , $user_id);
        if($like)
            return response(['message'=>'The gift has been liked before'],400);
        giftlike::create([
            'user_id'=>$user_id,
            'gift_id'=>$gift_id
        ]);
        giftUser::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->increment('gift_like');
        return response(['message'=>'The gift has successfully liked']);
    }

//_____________________ Is Like?
    function islike($gift_id , $user_id){
        $like=$this->like_check($gift_id , $user_id);
        if($like)
            return response(['message'=>'yes']);
        return response(['message'=>'no']);
    }

//_____________________ DisLike
    function dislike($gift_id , $user_id){
        $like=$this->like_check($gift_id , $user_id);
        if(!$like){
            return response(['message'=>'The gift hasnt been liked before'],400);
        }
        giftlike::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->delete();
        giftUser::where(['gift_id'=>$gift_id , 'user_id'=>$user_id])->decrement('gift_like');
        return response(['message'=>'The gift has successfully disliked']);
    }

//_____________________ Likes List
    function likeslist($gift_id){       
        try{
            $count=giftUser::where(['gift_id'=>$gift_id , 'gift_status'=>1])->first()->gift_like;
        }
        catch(\Exception $exception){
            return response(['message'=>'Gift not found'] , 400);
        }
        $likers=DB::table('giftlike')
        ->join('users','giftlike.user_id','=','users.id')
        ->where('giftlike.gift_id',$gift_id)
        ->select('user_id','name','family','userImageUrl','giftlike.created_at as like_date')
        ->get()->sortByDesc('like_date')->values();
        return response(['likes'=>$count,'users'=>$likers]);
    }


//_____________________ View ???????????
    function view($gift_id){
        giftUser::where('gift_id',$gift_id)->increment('gift_view');
        return response(['message'=>'view increased']);
    }

//_____________________ share
    function share($gift_id){
        giftUser::where('gift_id',$gift_id)->increment('shared');
        return response(['message'=>'share increased']);
    }

//_____________________ Remove
    function gift_remove($gift_id , $user_id){
        $gift=giftUser::where(['gift_id'=>$gift_id , 'user_id'=>$user_id , 'gift_status'=>1])->first();
        if(!$gift)
            return response(['message'=>'Gift not found'],400);
        giftUser::where(['gift_id'=>$gift_id , 'user_id'=>$user_id , 'gift_status'=>1])->update(['gift_status'=>0]);
        return response(['message'=>'The gift has removed successfully']);
    }

//_____________________ Update TEST & COMMIT
    function update_gift(Request $req){
        $gift=giftUser::where(['gift_id'=>$req->giftid , 'user_id'=>$req->userid , 'gift_status'=>1])->first();
        if(!$gift)
            return response(['message'=>'Gift not found'],400);

        if($req->g_name){
            $req->validate([
                'g_name'=>'max:100'
            ]);
            gift::where('id',$req->giftid)->update(['giftName'=>$req->g_name]);
        }
        if($req->g_price){
            $req->validate([
                'g_price'=>'numeric | max:60'
            ]);
            gift::where('id',$req->giftid)->update(['giftPrice'=>$req->g_price]);
        }
        if($req->g_desc)
            gift::where('id',$req->giftid)->update(['giftDesc'=>$req->g_desc]);
        if($req->g_link)
            gift::where('id',$req->giftid)->update(['giftUrl'=>$req->g_link]);
        if($req->g_image)
            gift::where('id',$req->giftid)->update(['giftImageUrl'=>$req->g_image]);
        if($req->g_rate)
            giftUser::where(['gift_id'=>$req->giftid,'user_id'=>$req->userid])->update(['desire_rate'=>$req->g_rate]);
        return response(['message'=>'The gift has updated successfully']);
    }

}
