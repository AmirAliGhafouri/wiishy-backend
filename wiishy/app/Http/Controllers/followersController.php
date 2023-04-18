<?php

namespace App\Http\Controllers;

use App\Models\userFollwerCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class followersController extends Controller
{
    //_____________________ All the followers of a user 
    function user_followers($user_id){
        $followers=DB::table('users')
        ->join('userfollowers','users.id','=','userfollowers.follower_id')
        ->where(['userfollowers.user_id'=>$user_id , 'follower_status'=>1])
        ->select('follower_id','userImageUrl' , 'name' , 'family')
        ->get();
        try{
            $followers_counte=userFollwerCount::where('user_id' , $user_id)->first()->followers;
        }
        catch(\Exception $exception){
            return response(['message'=>'user not found'] , 500);
        }
        return response(['followers_count'=>$followers_counte , 'followers'=>$followers]);
    }   
}
