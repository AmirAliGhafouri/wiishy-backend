<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class userController extends Controller
{
//_____________________ User Profile
    function user_profile($user_id){
        try{
            $user=User::findOrFail($user_id);
        }
        catch(\Exception $exception){
            return response(['message'=>'user not found'] , 500);
        }
        return response(['user'=>$user]);
    }

     /*  function adduser(Request $req){
        $user= new user;
        $user->name=$req->name;
        $user->family=$req->family;
        $user->userBirthday=$req->birth;
        $user->userLocationid=$req->loc;
        $user->userGender=$req->gender;
        $user->userDescription=$req->desc;
        $user->userImageUrl=$req->url;
        $user->status=$req->status;
        $user->userCode=$req->code;
        $user->save();
        return response(['message'=>'done'],200);
    } */
}
