<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGiftRequest;
use App\Http\Requests\UpdateGiftRequest;
use App\Http\Resources\exploreResource;
use App\Http\Resources\followingsGiftResource;
use App\Http\Resources\giftDetailResource;
use App\Http\Resources\UserGiftResource;
use App\Models\Category;
use App\Models\gift;
use App\Repositories\giftRepository;
use App\Repositories\likeRepository;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * this class is for gift management
 */
class giftsController extends Controller
{
    
    /**
     * All the gifts of a user
     * 
     * @param int $userId
     */
    public function user_gifts($userId)
    {
        $gifts = giftRepository::all($userId);
        $gift_user = UserGiftResource::collection($gifts);
        return response([
            'status' => 'success',
            'gifts' => $gift_user
        ], 200);
    }

    /**
     * All the gifts of a user Based on my_product
     * 
     * @param int $userId
     * @param int $myProduct
     */
    public function user_products($userId, $myProduct)
    {
        if ($myProduct != "1" or $myProduct != "0") {
            return response([
                'status' => 'Error',
                'message' => 'Wrong product status'
            ], 400);
        }

        $gifts = giftRepository::all_basedOnProduct($userId, $myProduct);
        $giftUser = UserGiftResource::collection($gifts);

        return response([
            'status' => 'success',
            'gifts' => $giftUser
        ], 200);
    }

    /**
     * A complete gift detail of a user
     * 
     * @param \Illuminate\Http\Request $req
     */
    public function gift_detail(Request $req)
    {
        $details = giftRepository::gift_details($req->giftid);
        $giftDetails = giftDetailResource::collection($details);

        $userId = $req->user()->id;
        $like = likeRepository::check($req->giftid, $userId);

        return response([
            'status' => 'success',
            'islike' => $like,
            'gift_detail' => $giftDetails
        ]);
    }
    
    /**
     * All the gifts of the users followings
     * 
     * @param \Illuminate\Http\Request $req
     */
    public function followings_gift(Request $req)
    {
        $userId = $req->user()->id;
        $gifts = giftRepository::followings_gift($userId);
        $followings_gift = followingsGiftResource::collection($gifts);
        return $followings_gift->additional([
            'pagination' => [
                'total' => $gifts->total(),
                'count' => $gifts->count(),
                'per_page' => $gifts->perPage(),
                'current_page' => $gifts->currentPage(),
                'total_pages' => $gifts->lastPage(),
            ],
        ]);
   /*      $count=$gifts->count();
        return response(['followings_gifts_count'=>$count ,'followings_gifts'=>$followings_gift]); */
    }

    /**
     * Explore
     * 
     * @param  \Illuminate\Http\Request $req
     */
    public function gift_explore(Request $req)
    {
        $userId = $req->user()->id;
        $list = giftRepository::list($userId);
        $explore = exploreResource::collection($list);
        return response([
            'status' => 'success',
            'explore' => $explore
        ], 200);
    }

    /**
     * Add New Gift
     * 
     * @param \App\Http\Requests\CreateGiftRequest $req
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function add_gift(CreateGiftRequest $req)
    { 
        $request = collect($req)->toArray();
        $userId = $req->user()->id;
        if ($req->image) {
            $fileName = $req->image->getClientOriginalName();
            Storage::disk('public')->putFileAs('/gifts', $req->image, $fileName);
            unset($request['image']);
            $request['gift_image_url'] = '/uploads/gifts/' . $fileName . '?t=' . Carbon::now()->getTimestamp();
        }
            
        $request['user_id'] = $userId;
        $gift = giftRepository::create($request);

        return response([
            'status' => $gift ? 'success' : 'Error',
            'message'=> $gift ? 'The gift is added successfully' : 'Failed to add gift',
            'gift'=>$gift
        ]);
    }

    /**
     * increase count of view of a gift
     * 
     * @param $giftId
     */
    public function view($giftId)
    {
        giftRepository::increase($giftId, 'gift_view');
        return response(['message' => 'view increased']);
    }

    /**
     * increase count of share of a gift
     * 
     * @param $giftId
     */
    function share($giftId){
        giftRepository::increase($giftId, 'shared');
        return response(['message' => 'share increased']);
    }

    /**
     * Delete a gift
     * 
     * @param int $giftId
     * @param int $userId
     */
    public function gift_remove($giftId, $userId)
    {
        $gift = giftRepository::get($giftId, $userId);
        if(!$gift) {
            return response([
                'status' => 'Error',
                'message' => 'Gift not found'
            ], 400);
        }
            
        $result = giftRepository::destroy($giftId, $userId);
        return response([
            'status' => $result ? 'success' : 'Error',
            'message'=> $result ? 'The gift is removed successfully' : 'faild to remove'
        ],$result ? 200 : 400);
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
        //giftRepository::updaexte($req->giftid, $request);
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

//_____________________ different kinds of price units
    function price_units(){
        $units=giftRepository::units();
        return response([
            'status'=>'success',
            'price_units'=>$units
        ],200);
    }
}
