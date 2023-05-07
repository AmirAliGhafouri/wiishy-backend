<?php

namespace App\Http\Controllers;

use App\Repositories\likeRepository;

class likeController extends Controller
{
//_____________________ Like a gift
    function like($gift_id , $user_id){
        $like=likeRepository::check($gift_id , $user_id);
        if($like)
            return response(['message'=>'The gift has been liked before'],400);

        likeRepository::like($gift_id , $user_id);
        likeRepository::increase($gift_id , $user_id);
        return response(['message'=>'The gift has successfully liked'],200);
    }

//_____________________ Is Like?
    function islike($gift_id , $user_id){
        $like=likeRepository::check($gift_id , $user_id);
        if($like)
            return response(['message'=>'yes']);
        return response(['message'=>'no']);
    }

//_____________________ DisLike
    function dislike($gift_id , $user_id){
        $like=likeRepository::check($gift_id , $user_id);
        if(!$like){
            return response(['message'=>'The gift hasnt been liked before'],400);
        }
        likeRepository::dislike($gift_id , $user_id);
        likeRepository::decrease($gift_id , $user_id);      
        return response(['message'=>'The gift has successfully disliked'],200);
    }

//_____________________ Likes List
    function likeslist($gift_id){       
        try{
            $count=likeRepository::count($gift_id);
        }
        catch(\Exception $exception){
            return response(['message'=>'Gift not found'] , 400);
        }
        $likers=likeRepository::list($gift_id);
        return response(['like_count'=>$count,'users'=>$likers],200);
    }

}
