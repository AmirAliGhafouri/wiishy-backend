<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
        $user=userRepository::create($req->toArray());
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

//_____________________ Update User
    function update(UpdateUserRequest $req){
        $user=userRepository::get($req->userid);
        if(!$user)
            return response(['message'=>'User not found'],400);
            
        if($req->user_name)
            userRepository::update($req->userid, $req->user_name, 'name');       
        if($req->user_family)
            userRepository::update($req->userid, $req->user_family, 'family');
        if($req->user_birthday)
            userRepository::update($req->userid, $req->user_birthday, 'userBirthday');
        if($req->user_location)
            userRepository::update($req->userid, $req->user_location, 'userLocationid');
        if($req->user_gender)
            userRepository::update($req->userid, $req->user_gender, 'userGender');
        if($req->user_description)
            userRepository::update($req->userid, $req->user_description, 'userDescription');
        if($req->user_image)
            userRepository::update($req->userid, $req->user_image, 'userImageUrl');
        return response(['message'=>'UserProfile has updated successfully']);
    }

}
