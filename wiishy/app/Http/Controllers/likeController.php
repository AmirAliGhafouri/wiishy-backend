<?php

namespace App\Http\Controllers;

use App\Repositories\likeRepository;

class likeController extends Controller
{
//_____________________ Like a gift
    function like($gift_id , $user_id){
        $like=likeRepository::like_check($gift_id , $user_id);
        if($like)
            return response(['message'=>'The gift has been liked before'],400);

        likeRepository::like($gift_id , $user_id);
        likeRepository::like_increase($gift_id , $user_id);
        return response(['message'=>'The gift has successfully liked'],200);
    }

//_____________________ Is Like?
    function islike($gift_id , $user_id){
        $like=likeRepository::like_check($gift_id , $user_id);
        if($like)
            return response(['message'=>'yes']);
        return response(['message'=>'no']);
    }

//_____________________ DisLike
    function dislike($gift_id , $user_id){
        $like=likeRepository::like_check($gift_id , $user_id);
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

}
