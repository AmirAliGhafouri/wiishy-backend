<?php

namespace App\Http\Resources;

use App\Repositories\giftRepository;
use App\Repositories\userRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class giftDetailResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'id'=>$this->id,
            'gift_name'=>$this->gift_name,
            'gift_price'=>$this->gift_price,
            'price_unit'=>giftRepository::price_unit($this->price_unit_id),
            'gift_desc'=>$this->gift_desc,
            'gift_url'=>$this->gift_url,
            'gift_image_url'=>$this->gift_image_url,
            'gift_like'=>$this->gift_like,
            'gift_view'=>$this->gift_view,
            'shared'=>$this->shared,
            'desire_rate'=>$this->desire_rate,
            'created_at'=>$this->gifts_created_at,
            'name'=>$this->name,
            'family'=>$this->family,
            'user_image_url'=>$this->user_image_url,
            'age'=>userRepository::age($this->user_birthday),
        ];
    }
}
