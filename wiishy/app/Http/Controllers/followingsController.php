<?php

namespace App\Http\Controllers;

use App\Models\userfollowingcount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class followingsController extends Controller
{
//_____________________ All the following of a user ??
    function user_followings($user_id){
        $followings=DB::table('users')
        ->join('userfollowings','users.id','=','userfollowings.following_id')
        ->where(['userfollowings.user_id'=>$user_id , 'following_status'=>1])
        ->select('following_id','userImageUrl' , 'name' , 'family')
        ->get();
        try{
            $followings_counte=userfollowingcount::where('user_id' , $user_id)->first()->followings;
        }
        catch(\Exception $exception){
                return response(['message'=>'user not found'] , 500);
        }
        
        return response(['followings_count'=>$followings_counte , 'followings'=>$followings]);
    }
}
