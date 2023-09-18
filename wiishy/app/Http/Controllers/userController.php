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
        $request =collect($req->validated())->filter(function($item){
            return $item != null;
        })->toArray();
        userRepository::update($req->userid, $request);       
        return response(['message'=>'UserProfile has updated successfully']);
    }

//_____________________ Auth
    function auth(CreateUserRequest $req ,$provider){
        $user_provider=userRepository::provider($provider);;
        if(!$user_provider)
            return response(['message'=>'Wrong provider'],400);

        $user=userRepository::check($req,$user_provider->id);
        if(!$user){
            $req['provider_id']=$user_provider->id;
            $register=userRepository::create($req->toArray());
            $token= userRepository::token($register);
            return response([
                'status'=>'success',
                'new_user'=>true,
                'message'=>'Registration is done successfully',
                'token'=>$token,
                'user'=>$register
            ],200);
        }

        $token= userRepository::token($user);
        return response([
            'status'=>'success',
            'new_user'=>false,
            'message'=>'User logged in successfully',
            'token'=>$token,
            'user'=>$user
        ],200);       
    }

//_____________________ Unauthenticated
    function Unauthenticated(){
        return response(['message'=>'please Login first'],401);
    }

}
