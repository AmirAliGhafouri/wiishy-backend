<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGiftRequest;
use App\Models\gift;
use App\Models\giftlike;
use App\Models\giftUser;
use App\Repositories\giftRepository;
use App\Repositories\likeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class giftsController extends Controller
{
    
//_____________________ All the gifts of a user
    function user_gifts($user_id){
        $gifts=giftRepository::user_gift($user_id);
        return response(['gifts'=>$gifts]);
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
        $count=$gifts->count();
        return response(['followings_gifts_count'=>$count ,'followings_gifts'=>$gifts]);
    }

//_____________________ Add New Gift
    function add_gift(CreateGiftRequest $req , $user_id){
        $gift=giftRepository::gift_create($req);
        giftRepository::giftuser_create($req,$gift->id);
        return response(['message'=>"The gift has been successfully added"]);
    }

//_____________________ View ???????????
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
            return response(['message'=>'Gift not found'],400);
        giftRepository::destroy($gift_id , $user_id);
        return response(['message'=>'The gift has removed successfully']);
    }

//_____________________ Update TEST & COMMIT
    function update_gift(Request $req){
        $gift=giftUser::where(['gift_id'=>$req->giftid , 'user_id'=>$req->userid , 'gift_status'=>1])->first();
        if(!$gift)
            return response(['message'=>'Gift not found'],400);

        if($req->g_name){
            $req->validate([
                'g_name'=>'max:100'
            ]);
            gift::where('id',$req->giftid)->update(['giftName'=>$req->g_name]);
        }
        if($req->g_price){
            $req->validate([
                'g_price'=>'numeric'
            ]);
            gift::where('id',$req->giftid)->update(['giftPrice'=>$req->g_price]);
        }
        if($req->g_desc)
            gift::where('id',$req->giftid)->update(['giftDesc'=>$req->g_desc]);
        if($req->g_link)
            gift::where('id',$req->giftid)->update(['giftUrl'=>$req->g_link]);
        if($req->g_image)
            gift::where('id',$req->giftid)->update(['giftImageUrl'=>$req->g_image]);
        if($req->g_rate)
            giftUser::where(['gift_id'=>$req->giftid,'user_id'=>$req->userid])->update(['desire_rate'=>$req->g_rate]);
        return response(['message'=>'The gift has updated successfully']);
    }

}
