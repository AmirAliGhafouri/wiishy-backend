<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Resources\userProfileResource;
use App\Repositories\userRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Model\User;
use App\Repositories\giftRepository;

class userController extends Controller
{
    /**
     * All detail of a user
     * 
     * @param int $user_id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function user_profile($user_id)
    {
        $user = userRepository::all($user_id);

        // checing if user is exist
        if (!$user) {
            return response([
                'status' => 'Error',
                'message' => 'not found'
            ], 400);
        }

        $profile = userProfileResource::make($user);
        return response([
            'status' => 'sucess',
            'users' => $profile
            ]);
    }

    /**
     * Home page where you can see followings gifts
     * 
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function home(Request $req){
        $user_id = $req->user()->id;
        $user = userRepository::all($user_id);
        $gifts = giftRepository::all($user_id);
            return response([
                'status' => 'success',
                'user' => $user,
                'gifts' => $gifts
            ], 200);
    }

    /**
     * List of all users
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function user_list(){
        $list = userRepository::list();
        return response([
            'status' => $list ? 'success' : 'Error',
            'user' => $list
        ], $list ? 200 : 400);
    }

    /**
     * delete a user from DataBase
     * 
     * @param int $userId
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function remove($userId){
        $remove = userRepository::destroy($userId);
          
        return response([
            'status' => $remove ? 'success' : 'Error',
            'message' => $remove ? 'User is removed successfully' : 'User not found'
        ], $remove ? 200 : 400);
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
        Log::debug('updateUserRequest', [$req->all()]);
        if(!$req->all()){
            return response([
                'status' => 'Error',
                'message' => 'Empty request'
            ], 400);
        }
        $user_id = $req->user()->id;
        $user = userRepository::get($user_id);
        if(!$user) {
            return response([
                'status' => 'Error',
                'message' => 'User not found'
            ], 400);
        }
           
        $request = collect($req->validated())->filter(function($item){
            return $item != null;
        })->toArray();
        if($req->image){
            $fileName = $req->user()->id.'.'.$req->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('/users', $req->image, $fileName);
            unset($request['image']);
            $request['user_image_url'] = '/uploads/users/' . $fileName.'?t='.Carbon::now()->getTimestamp();
        }
        userRepository::update($user_id, $request);       
        return response([
            'status' => 'success',
            'message' => 'UserProfile has updated successfully'
        ]);
    }




//_____________________ Auth
    function auth(CreateUserRequest $req, $provider){
        $user_provider = userRepository::provider($provider);
        if (!$user_provider) {
            return response([
                'status' => 'Error',
                'message' => 'Wrong provider'
            ], 400);
        }        

        $user = userRepository::check($req,$user_provider->id);
        if (!$user) {
            $req['provider_id'] = $user_provider->id;
            $register = userRepository::create($req->toArray());
            $token = userRepository::token($register);
            return response([
                'status' => 'success',
                'new_user' => true,
                'message' => 'Registration is done successfully',
                'token' => $token,
                'user' => $register
            ], 200);
        }

        $token = userRepository::token($user);
        return response([
            'status' => 'success',
            'new_user' => false,
            'message' => 'User logged in successfully',
            'token' => $token,
            'user' => $user
        ], 200);       
    }

//_____________________ User Search
    function search(UserSearchRequest $req){
        if (!$req->user_search) {
            return response([
                'status' => 'Error',
                'message' => "not found"
            ], 400);
        }
        $user_search = str_replace(" ", '%', $req->user_search);
        $search = userRepository::search($user_search);
        return response([
            'status' => 'success',
            'search' => $search
        ], 200);
    }

//_____________________ Unauthenticated
    public function Unauthenticated()
    {
        return response([
            'message'=>'please Login first'
        ], 401);
    }


}
