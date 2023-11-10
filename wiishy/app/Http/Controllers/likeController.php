<?php

namespace App\Http\Controllers;

use App\Repositories\likeRepository;

class likeController extends Controller
{
//_____________________ Like a gift
    function like($gift_id , $user_id){
        $like=likeRepository::check($gift_id , $user_id);
        if($like)
            return response([
                'status'=>'Error',
                'message'=>'The gift has been liked before'
            ],400);

        likeRepository::like($gift_id , $user_id);
        likeRepository::increase($gift_id);
        return response([
            'status'=>'success',
            'message'=>'The gift is liked successfully'
        ],200);
    }

//_____________________ Is Like?
    function islike($gift_id , $user_id){
        $like=likeRepository::check($gift_id , $user_id);
        return response(['islike'=>$like],200);
    }

//_____________________ DisLike
    function dislike($gift_id , $user_id){
        $like=likeRepository::check($gift_id , $user_id);
        if(!$like){
            return response([
                'status'=>'Error',
                'message'=>'The gift hasnt been liked before'
            ],400);
        }
        likeRepository::dislike($gift_id , $user_id);
        likeRepository::decrease($gift_id);      
        return response([
            'status'=>'success',
            'message'=>'The gift is disliked successfully'
        ],200);
    }

//_____________________ Likes List
    function likeslist($gift_id){       
        try{
            $count=likeRepository::count($gift_id);
        }
        catch(\Exception $exception){
            return response([
                'status'=>'Error',
                'message'=>'Gift not found'
            ] , 400);
        }
        $likers=likeRepository::list($gift_id);
        return response([
            'status'=>'success',
            'like_count'=>$count,
            'users'=>$likers
        ],200);
    }

}
