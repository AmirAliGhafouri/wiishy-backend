<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGiftRequest;
use App\Http\Requests\UpdateGiftRequest;
use App\Http\Resources\followingsGiftResource;
use App\Http\Resources\UserGiftResource;
use App\Repositories\giftRepository;
use App\Repositories\likeRepository;
use Illuminate\Http\Request;

class giftsController extends Controller
{
    
//_____________________ All the gifts of a user
    function user_gifts($user_id,$id){
        $gifts=giftRepository::all($user_id);
        $gift_user=UserGiftResource::collection($gifts,$id);
        return response(['gifts'=>$gift_user]);
    }

//_____________________ A complete gift detail of a user
    function gift_detail($gift_id , $user_id){
        $gift_detail=giftRepository::gift_details($gift_id , $user_id);
        $like=likeRepository::check($gift_id , $user_id);
        return response(['islike'=>$like,'gift_detail'=>$gift_detail]);
    }

//_____________________ All the gifts of the users followings
    function followings_gift($id){
        $gifts=giftRepository::followings_gift($id);
        $followings_gift=followingsGiftResource::collection($gifts,$id);
        $count=$gifts->count();
        return response(['followings_gifts_count'=>$count ,'followings_gifts'=>$followings_gift]);
    }

//_____________________ Add New Gift
    function add_gift(CreateGiftRequest $req , $user_id){
        $request=collect($req)->toArray();
        $request['user_id']=$user_id;
        $gift=giftRepository::create($request);
        if(!$gift){
            return response([
                'status'=>'Error',
                'message'=>"Failed to add gift"
            ]);
        }

        return response([
            'status'=>'success',
            'message'=>'The gift has been successfully added',
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
            return response(['message'=>'Gift not found'],400);
  
        $request =collect($req->validated())->filter(function($item){
            return $item != null;
        })->toArray();

        giftRepository::update($req->giftid, $request);
        return response(['message'=>'The gift has updated successfully']);
    }

//_____________________ search
    function search(Request $req){
        $gift_search=str_replace(" ",'%',$req->gift_search);
        $search=giftRepository::search($gift_search);
        if(!$search->all())
            return response(['message'=>"not found"],400);
        return response(['search'=>$search]);
    }

}
