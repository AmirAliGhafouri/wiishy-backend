<?php

namespace App\Http\Controllers;

use App\Repositories\likeRepository;

/**
 * this class is for likes managment
 */
class likeController extends Controller
{
    /**
     * like a gift
     * 
     * @param int $giftId
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function like($giftId, $userId)
    {
        $giftLike = likeRepository::check($giftId, $userId);

        // check if the gift is liked or no
        if ($giftLike) {
            return response([
                'status' => 'Error',
                'message' => 'The gift has been liked before'
            ], 400);
        }
        
        $error = false;
        $like = likeRepository::like($giftId, $userId);
        if ($like) {
            $increase = likeRepository::increase($giftId);
        }

        if (!$like || !$increase) {
            $error = true;
        }

        return response([
            'status' => $error ? 'Error' : 'success',
            'message' => $error ? 'something went wrong' : 'The gift is liked successfully'
        ], $error ? 400 : 200);
    }

    /**
     * check if a gift is liked or no
     * 
     * @param int $giftId
     * @param int $userId
     */
    public function islike($giftId, $userId)
    {
        $like = likeRepository::check($giftId, $userId);
        return response([
            'islike' => $like
        ], 200);
    }

    /**
     * Dislike a gift
     */
    public function dislike($giftId, $userId)
    {
        $like=likeRepository::check($giftId, $userId);
        if (!$like) {
            return response([
                'status' => 'Error',
                'message' => 'The gift hasnt been liked before'
            ],400);
        }
        likeRepository::dislike($giftId, $userId);
        likeRepository::decrease($giftId);      
        return response([
            'status' => 'success',
            'message' => 'The gift is disliked successfully'
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
