<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGiftRequest;
use App\Http\Requests\UpdateGiftRequest;
use App\Http\Resources\followingsGiftResource;
use App\Http\Resources\UserGiftResource;
use App\Models\gift;
use App\Models\giftlike;
use App\Models\giftUser;
use App\Repositories\giftRepository;
use App\Repositories\giftUserRepository;
use App\Repositories\likeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class giftsController extends Controller
{
    
//_____________________ All the gifts of a user
    function user_gifts($user_id,$id){
        $gifts=giftRepository::user_gift($user_id);
        $gift_user=UserGiftResource::collection($gifts,$id);
        return response(['gifts'=>$gift_user]);
    }

//_____________________ A complete gift detail of a user
    function gift_detail($gift_id , $user_id){
        $gift_detail=giftRepository::gift_details($gift_id , $user_id);
        $like=likeRepository::check($gift_id , $user_id);
        return response(['like'=>$like,'gift_detail'=>$gift_detail]);
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
        $gift=giftRepository::create($req);
        giftUserRepository::create($req,$gift->id);
        return response(['message'=>"The gift has been successfully added"]);
    }

//_____________________ View ???????????
    function view($gift_id){
        giftUserRepository::increase($gift_id,'gift_view');
        return response(['message'=>'view increased']);
    }

//_____________________ share
    function share($gift_id){
        giftUserRepository::increase($gift_id,'shared');
        return response(['message'=>'share increased']);
    }

//_____________________ Remove
    function gift_remove($gift_id , $user_id){
        $gift=giftUserRepository::get($gift_id , $user_id);
        if(!$gift)
            return response(['message'=>'Gift not found'],400);
            giftUserRepository::destroy($gift_id , $user_id);
        return response(['message'=>'The gift has removed successfully']);
    }

//_____________________ Update
    function update_gift(UpdateGiftRequest $req){
        $gift=giftUserRepository::get($req->giftid , $req->userid);
        if(!$gift)
            return response(['message'=>'Gift not found'],400);

        if($req->g_name)
            giftRepository::update($req->giftid, $req->g_name, 'giftName');
        if($req->g_price)        
            giftRepository::update($req->giftid, $req->g_price, 'giftPrice');
        if($req->g_desc)
            giftRepository::update($req->giftid, $req->g_desc, 'giftDesc');
        if($req->g_link)
            giftRepository::update($req->giftid, $req->g_link, 'giftUrl');
        if($req->g_image)
            giftRepository::update($req->giftid, $req->g_image, 'giftImageUrl');
        if($req->g_rate)
            giftUserRepository::update($req->giftid, $req->g_image, 'giftImageUrl');
        return response(['message'=>'The gift has updated successfully']);
    }

}
