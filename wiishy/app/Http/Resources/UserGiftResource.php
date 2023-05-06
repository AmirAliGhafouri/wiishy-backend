<?php

namespace App\Http\Resources;

use App\Repositories\likeRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGiftResource extends JsonResource
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
            'gift_id'=>$this->gift_id,
            'gift_view'=>$this->gift_view,
            'gift_like'=>$this->gift_like,
            'desire_rate'=>$this->desire_rate,
            'created_at'=>$this->giftuserCreated_at,
            'giftName'=>$this->giftName,
            'giftPrice'=>$this->giftPrice,
            'giftDesc'=>$this->giftDesc,
            'giftUrl'=>$this->giftUrl,
            'islike'=>likeRepository::check($this->gift_id,$request->id),
        ];
    }
}
