<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class followingsController extends Controller
{
//_____________________ All the following of a user ??
    function user_followings($user_id){
        $followings=DB::table('users')
        ->join('userfollows','users.id','=','userfollows.follow_id')
        ->where(['userfollows.user_id'=>$user_id , 'follow_status'=>1])
        ->select('follow_id as user_id','userImageUrl' , 'name' , 'family')
        ->get();
        try{
            $followings_counte=User::where('id' , $user_id)->first()->followings;
        }
        catch(\Exception $exception){
                return response(['message'=>'user not found'] , 400);
        }
        
        return response(['followings_count'=>$followings_counte , 'followings'=>$followings]);
    }
}
