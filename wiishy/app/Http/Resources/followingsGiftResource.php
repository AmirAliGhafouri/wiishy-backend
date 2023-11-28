<?php

namespace App\Http\Resources;

use App\Repositories\giftRepository;
use App\Repositories\likeRepository;
use App\Repositories\userRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class followingsGiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user_id'=>$this->uid,
            'gift_id'=>$this->gift_id,
            'gift_name'=>$this->gift_name,
            'gift_price'=>$this->gift_price,
            'price_unit'=>giftRepository::price_unit($this->price_unit_id),
            'gift_url'=>$this->gift_url,
            'gift_image_url'=>$this->gift_image_url,
            'islike'=>likeRepository::check($this->id,$request->user()->id),
            'gift_like'=>$this->gift_like,
            'gifts_created_at'=>$this->gifts_created_at,
            'name'=>$this->name,
            'family'=>$this->family,
            'user_image_url'=>$this->user_image_url,
            'age'=>userRepository::age($this->user_birthday),
        ];
    }
}
