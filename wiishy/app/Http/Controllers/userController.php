<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Repositories\userRepository;
use Illuminate\Http\Request;

class userController extends Controller
{
//_____________________ User Profile
    function user_profile($user_id){
        $user=userRepository::all($user_id);
        return response(['user'=>$user]);
    }

//_____________________ ADD User
    function add_user(CreateUserRequest $req){
        $user=userRepository::create($req);
        if(!$user)
            return response(['message'=>'Fail to add user'],400);
        return response(['message'=>'User has added successfully'],200);
    }

//_____________________ Remove User
    function remove($user_id){
        $user=userRepository::destroy($user_id);
        if(!$user)
            return response(['message'=>'User not found'],400);
        return response(['message'=>'User has removed successfully'],200);
    }

//_____________________ ADD User
    function update(Request $req){
        $user=User::where(['id'=>$req->userid,'status'=>1])->first();
        if(!$user)
            return response(['message'=>'User not found'],400);
        if($req->user_name){
            $req->validate([
                'user_name'=>'max:60'
            ]);
            User::where('id',$req->userid)->update(['name'=>$req->user_name]);
        }
        if($req->user_family){
            $req->validate([
                'user_family'=>'max:60'
            ]);
            User::where('id',$req->userid)->update(['family'=>$req->user_family]);
        }
        if($req->user_birthday){
            $req->validate([
                'user_birthday'=>'date'
            ]);
            User::where('id',$req->userid)->update(['userBirthday'=>$req->user_birthday]);
        }
        if($req->user_location){
            $req->validate([
                'user_location'=>'integer'
            ]);
            User::where('id',$req->userid)->update(['userLocationid'=>$req->user_location]);
        }
        if($req->user_gender){
            $req->validate([
                'user_gender'=>'integer'
            ]);
            User::where('id',$req->userid)->update(['userGender'=>$req->user_gender]);
        }
        if($req->user_description)
            User::where('id',$req->userid)->update(['userDescription'=>$req->user_description]);
        if($req->user_image)
            User::where('id',$req->userid)->update(['userImageUrl'=>$req->user_image]);
        return response(['message'=>'UserProfile has updated successfully']);
    }

}
