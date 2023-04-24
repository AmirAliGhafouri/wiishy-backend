<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class followersController extends Controller
{
    //_____________________ All the followers of a user 
    function user_followers($user_id){
        $followers=DB::table('users')
        ->join('userfollows','users.id','=','userfollows.user_id')
        ->where(['userfollows.follow_id'=>$user_id , 'follow_status'=>1])
        ->select('userfollows.user_id','userImageUrl' , 'name' , 'family')
        ->get();
        try{
            $followers_counte=User::where('id' , $user_id)->first()->followers;
        }
        catch(\Exception $exception){
            return response(['message'=>'user not found'] , 400);
        }
        return response(['followers_count'=>$followers_counte , 'followers'=>$followers]);
    }   
}
