<?php

namespace App\Http\Controllers;

use App\Http\Resources\followerListResource;
use App\Http\Resources\followingListResource;
use App\Http\Resources\followSuggestionResource;
use App\Repositories\followRepository;
use Illuminate\Http\Request;

/**
 * This class is for Follow management
 */
class followController extends Controller
{
    /**
     * Followers of a user
     * 
     * @param \Illuminate\Http\Request $req
     */
    public function followers(Request $req)
    {
        try{
            $followers_count = followRepository::count($req->id, 'followers');
        } catch(\Exception $exception){
            return response([
                'status' => 'Error',
                'message' => 'user not found'
            ], 400);
        }

        $user_id = $req->user()->id;
        $followers = followRepository::followers($req->id);
        $list = followerListResource::collection($followers, $user_id);

        return response([
            'status' => 'success',
            'followers_count' => $followers_count,
            'followers' => $list
        ], 200);
    }  
    
    /**
     * followings of a user
     * 
     * @param \Illuminate\Http\Request $req
     */
    public function followings(Request $req)
    {
        try{
            $followings_count = followRepository::count($req->id, 'followings');
        } catch(\Exception $exception){
            return response([
                'status' => 'Error',
                'message' => 'user not found'
            ] , 400);
        }

        $user_id = $req->user()->id;
        $followings = followRepository::follow_list($req->id, 'userfollows.follow_id', 'userfollows.user_id');  
        $list = followingListResource::collection($followings, $user_id);

        return response([
            'status' => 'success',
            'followings_count' => $followings_count,
            'followings' => $list
        ], 200);
    }

    /**
     * Suggestions for users to follow
     * 
     * @param \Illuminate\Http\Request $req
     */
    public function suggestion(Request $req)
    {
        $user_id = $req->user()->id;

        // FOLLOWINGS followers
        $followings = followRepository::list($user_id, 'userfollows.follow_id', 'userfollows.user_id', $user_id);  
        $following_suggestions = followRepository::suggestions($followings, $user_id);
    
        // FOLLOWERS followers
        $followers = followRepository::list($user_id, 'userfollows.user_id', 'userfollows.follow_id', $user_id);  
        $follower_suggestions = followRepository::suggestions($followers, $user_id);

        $follow_suggestions_users = followRepository::unique($following_suggestions, $follower_suggestions);
        $remove_users_followings = followRepository::filter($follow_suggestions_users, $user_id);
        $user_details = followRepository::follow_suggestion($remove_users_followings);
        $list = followSuggestionResource::collection($user_details);

        return response([
            'status' => 'success',
            'suggestions' => $list
        ], 200);
    } 

    /**
     * check if someone is followed
     * 
     * @param int $giftId | int $userId
     */
    public function isfollow($userId, $followId)
    {
        $follow = followRepository::check($userId, $followId);
        return response([
            'status' => 'success',
            'isfollow' => $follow
        ], 200);
    }


    /**
     * follow proccess
     * 
     * @param int $userId
     * @param int $followId
     */
    public function follow($userId, $followId)
    {
        if ($userId == $followId) {
            return response([
                'status' => 'Error',
                'message' => 'you cant follow yourself !'
            ], 400);
        }

        $follow = followRepository::check($userId, $followId);
        if ($follow) {
            return response([
                'status' => 'Error',
                'message' => 'user has been already followed'
            ], 400);
        }        

        $result = followRepository::follow($userId, $followId);

        if ($result) {
            followRepository::increase($userId, 'followings');
            followRepository::increase($followId, 'followers');
        }


        return response([
            'status' => $result ? 'success' : 'Error',
            'message' => $result ? 'The follow process is done successfully' : 'Fail to follow'
        ], $result ? 200 : 400);
    }

    /**
     * Unfollow proccess
     * 
     * @param int $userId
     * @param int $followId
     */
    public function unfollow($userId, $followId)
    {
        $follow = followRepository::check($userId, $followId);
        if (!$follow) {
            return response([
                'status' => 'Error',
                'message' => 'user hasnt been followed'
            ], 400);
        }

        $result = followRepository::unfollow($userId, $followId);

        if($result) {
            followRepository::decrease($userId, 'followings');
            followRepository::decrease($followId, 'followers');    
        }
       
        return response([
            'status' => $result ? 'success' : 'Error',
            'message' => $result ? 'The Unfollow process is done successfully' : 'Fail to unfollow'
        ], $result ? 200 : 400);
    }
}
