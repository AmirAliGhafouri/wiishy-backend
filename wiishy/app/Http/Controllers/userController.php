<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\userProfileResource;
use App\Repositories\userRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class userController extends Controller
{
//_____________________ User Profile
    function user_profile($user_id){
        $user=userRepository::all($user_id);
        if(!$user){
            return response([
                'status'=>'Error',
                'message'=>'not found'
            ],400);
        }
        $profile=userProfileResource::make($user);
        return response(['users'=>$profile]);
    }

//_____________________ User List
    function user_list(){
        $list=userRepository::list();
        return response(['user'=>$list]);
    }

//_____________________ Remove User
    function remove($user_id){
        $user=userRepository::destroy($user_id);
        if(!$user)
            return response([
                'status'=>'Error',
                'message'=>'User not found'
            ],400);
        return response([
            'status'=>'success',
            'message'=>'User is removed successfully'
        ],200);
    }

//_____________________ Update User
   /*  function update(UpdateUserRequest $req){
        if(!$req->all()){
            return response([
                'status'=>'Error',
                'message'=>'Empty request'
            ],400);
        }

        $user=userRepository::get($req->userid);
        if(!$user)
            return response([
                'status'=>'Error',
                'message'=>'User not found'
            ],400);
        $request =collect($req->validated())->filter(function($item){
            return $item != null;
        })->toArray();
        userRepository::update($req->userid, $request);       
        return response([
            'status'=>'success',
            'message'=>'UserProfile updated is done successfully'
        ]);
    }
 */

//_____________________ Update User
function update(UpdateUserRequest $req){
    Log::debug('updateUserRequest',[$req->all()]);
    if(!$req->all()){
        return response([
            'status'=>'Error',
            'message'=>'Empty request'
        ],400);
    }
    $user=userRepository::get($req->userid);
    if(!$user)
        return response([
            'status'=>'Error',
            'message'=>'User not found'
        ],400);
    $request =collect($req->validated())->filter(function($item){
        return $item != null;
    })->toArray();
    if($req->image){
        $fileName = $req->user()->id.'.'.$req->image->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('/users',$req->image,$fileName);
        unset($request['image']);
        $request['user_image_url'] = '/uploads/users/' . $fileName.'?t='.Carbon::now()->getTimestamp();
    }
    userRepository::update($req->userid, $request);       
    return response([
        'status'=>'success',
        'message'=>'UserProfile has updated successfully'
    ]);
}




//_____________________ Auth
    function auth(CreateUserRequest $req ,$provider){
        $user_provider=userRepository::provider($provider);;
        if(!$user_provider)
            return response([
                'status'=>'Error',
                'message'=>'Wrong provider'
            ],400);

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
