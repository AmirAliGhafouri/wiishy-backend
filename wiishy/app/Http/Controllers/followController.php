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
    public function user_followers(Request $req)
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
        $followers = followRepository::follow_list($req->id, 'userfollows.user_id', 'userfollows.follow_id');
        $list = followerListResource::collection($followers, $user_id);
        return response([
            'status' => 'success',
            'followers_count' => $followers_count,
            'followers' => $list
        ], 200);
    }  
    
//_____________________ All the following of a user 
    function user_followings(Request $req){
        try{
            $followings_count=followRepository::count($req->id,'followings');
        }
        catch(\Exception $exception){
            return response([
                'status'=>'Error',
                'message'=>'user not found'
            ] , 400);
        }
        $user_id=$req->user()->id;
        $followings=followRepository::follow_list($req->id,'userfollows.follow_id','userfollows.user_id');  
        $list= followingListResource::collection($followings,$user_id);
        return response([
            'status'=>'success',
            'followings_count'=>$followings_count,
            'followings'=>$list
        ],200);
    }

//_____________________ Suggestions to users to follow
   function follow_suggestion(Request $req){
        $user_id=$req->user()->id;
        //FOLLOWINGS followers
        $followings=followRepository::list($user_id,'userfollows.follow_id','userfollows.user_id',$user_id);  
        $following_suggestions=followRepository::suggestions($followings,$user_id);
    
        //FOLLOWERS followers
        $followers=followRepository::list($user_id,'userfollows.user_id','userfollows.follow_id',$user_id);  
        $follower_suggestions=followRepository::suggestions($followers,$user_id);

        $follow_suggestions_users=followRepository::unique($following_suggestions,$follower_suggestions);
        $remove_users_followings=followRepository::filter($follow_suggestions_users,$user_id);
        $user_details=followRepository::follow_suggestion($remove_users_followings);
        $list= followSuggestionResource::collection($user_details);

        return response([
            'status'=>true,
            'suggestions'=>$list
        ],200);
    } 

//_____________________ IS Follow?
    function isfollow($user_id,$follow_id){
        $follow=followRepository::check($user_id,$follow_id);
        return response(['isfollow'=>$follow]);
    }


//_____________________ Follow
    function follow($user_id,$follow_id){
        if($user_id==$follow_id){
            return response([
                'status'=>'Error',
                'message'=>'you cant follow yourself !'
            ],400);
        }
        $follow=followRepository::check($user_id,$follow_id);
        if($follow)
            return response([
                'status'=>'Error',
                'message'=>'user has been already followed'
            ],400);

        followRepository::follow($user_id,$follow_id);

        followRepository::increase($user_id,'followings');
        followRepository::increase($follow_id,'followers');
        return response([
            'status'=>'success',
            'message'=>'The follow process is done successfully'
        ]);
    }

//_____________________ UnFollow
    function unfollow($user_id,$follow_id){
        $follow=followRepository::check($user_id,$follow_id);
        if(!$follow)
            return response([
                'status'=>'Error',
                'message'=>'user hasnt been followed'
            ],400);

        followRepository::unfollow($user_id,$follow_id);

        followRepository::decrease($user_id,'followings');
        followRepository::decrease($follow_id,'followers');    
        return response([
            'status'=>'success',
            'message'=>'The Unfollow process is done successfully'
        ]);
    }
}
