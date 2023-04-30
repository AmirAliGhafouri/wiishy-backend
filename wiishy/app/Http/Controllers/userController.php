<?php

namespace App\Http\Controllers;

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
    function add_user(Request $req){
        $req->validate([
            'user_name'=>'required | max:60',
            'user_family'=>'required | max:60',
            'user_birthday'=>'required | date',
            'user_location'=>'required | integer',
            'user_gender'=>'required | integer',
            'user_description'=>'required',
            'user_code'=>'required'
        ]);
        $add=User::create([
            'name'=>$req->user_name,
            'family'=>$req->user_family,
            'userBirthday'=>$req->user_birthday,
            'userLocationid'=>$req->user_location,
            'userGender'=>$req->user_gender,
            'userDescription'=>$req->user_description,
            'userImageUrl'=>$req->user_image,
            'userCode'=>$req->user_code
        ]);
        if(!$add)
            return response(['message'=>'Fail to add user'],400);
        return response(['message'=>'User has added successfully']);
    }

//_____________________ Remove User
    function remove($user_id){
        $user=User::where(['id'=>$user_id,'status'=>1])->update(['status'=>0]);
        if(!$user)
            return response(['message'=>'User not found'],400);
        return response(['message'=>'User has removed successfully']);
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
