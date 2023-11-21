<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGiftRequest;
use App\Http\Requests\UpdateGiftRequest;
use App\Http\Resources\exploreResource;
use App\Http\Resources\followingsGiftResource;
use App\Http\Resources\giftDetailResource;
use App\Http\Resources\UserGiftResource;
use App\Repositories\giftRepository;
use App\Repositories\likeRepository;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class giftsController extends Controller
{
    
//_____________________ All the gifts of a user
    function user_gifts($user_id){
        $gifts=giftRepository::all($user_id);
        $gift_user=UserGiftResource::collection($gifts);
        return response(['gifts'=>$gift_user]);
    }

//_____________________ A complete gift detail of a user
    function gift_detail(Request $req){
        $details=giftRepository::gift_details($req->giftid );
        $gift_details=giftDetailResource::collection($details);
        $user_id=$req->user()->id;
        $like=likeRepository::check($req->giftid , $user_id);
        return response(['islike'=>$like,'gift_detail'=>$gift_details]);
    }
    
//_____________________ All the gifts of the users followings
    function followings_gift(Request $req){
        $user_id=$req->user()->id;
        $gifts=giftRepository::followings_gift($user_id);
        $followings_gift=followingsGiftResource::collection($gifts);
        $count=$gifts->count();
        return response(['followings_gifts_count'=>$count ,'followings_gifts'=>$followings_gift]);
    }

//_____________________ Explore
    function gift_explore(Request $req){
        $user_id= $req->user()->id;
        $list=giftRepository::list($user_id);
        return response([
            'status'=>true,
            'explore'=>$list
        ],200);
    }

//_____________________ Add New Gift
    function add_gift(CreateGiftRequest $req){ 
        $request=collect($req)->toArray();
        $user_id=$req->user()->id;
        $file_name= $req->image->getClientOriginalName();
        Storage::disk('public')->putFileAs('/gifts',$req->image,$file_name);
        unset($request['image']);
        $request['gift_image_url'] = '/uploads/gifts/' . $file_name.'?t='.Carbon::now()->getTimestamp();

        $request['user_id'] = $user_id;
        $gift=giftRepository::create($request);
        if(!$gift){
            return response([
                'status'=>'Error',
                'message'=>"Failed to add gift"
            ]);
        }

        return response([
            'status'=>'success',
            'message'=>'The gift is added successfully',
            'gift'=>$gift
        ]);
    }

//_____________________ View
    function view($gift_id){
        giftRepository::increase($gift_id,'gift_view');
        return response(['message'=>'view increased']);
    }

//_____________________ share
    function share($gift_id){
        giftRepository::increase($gift_id,'shared');
        return response(['message'=>'share increased']);
    }

//_____________________ Remove
    function gift_remove($gift_id , $user_id){
        $gift=giftRepository::get($gift_id , $user_id);
        if(!$gift)
            return response([
                'status'=>'Error',
                'message'=>'Gift not found'
            ],400);
        giftRepository::destroy($gift_id , $user_id);
        return response([
            'status'=>'success',
            'message'=>'The gift is removed successfully'
        ],200);
    }

//_____________________ Update
    function update_gift(UpdateGiftRequest $req){      
        $gift=giftRepository::get($req->giftid , $req->userid);
        if(!$gift)
            return response([
                'status'=>'Error',
                'message'=>'Gift not found'
            ],400);
            
        if(!$req->all()){
            return response([
                'status'=>'Error',
                'message'=>'Empty request'
            ],400);
        }

        $request =collect($req->validated())->filter(function($item){
            return $item != null;
        })->toArray();

        if($req->image){
            $file_name= $req->image->getClientOriginalName();
            Storage::disk('public')->putFileAs('/gifts',$req->image,$file_name);
            unset($request['image']);
            $request['gift_image_url'] = '/uploads/gifts/' . $file_name.'?t='.Carbon::now()->getTimestamp();
        }

        giftRepository::update($req->giftid, $request);
        $newgift=giftRepository::get($req->giftid , $req->userid);
        return response([
            'status'=>'success',
            'message'=>'The gift is updated successfully',
            'gift'=>$newgift
        ],200);
    }

//_____________________ search
    function search(Request $req){
        $gift_search=str_replace(" ",'%',$req->gift_search);
        $search=giftRepository::search($gift_search);
        if(!$search->all()){
            return response([
                'status'=>'Error',
                'message'=>"not found"
            ],400);
        }
        return response([
            'status'=>'success',
            'search'=>$search
        ],200);
    }

}
