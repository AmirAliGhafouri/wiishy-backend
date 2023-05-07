<?php

namespace App\Http\Resources;

use App\Repositories\likeRepository;
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
            'user_id'=>$this->user_id,
            'gift_id'=>$this->gift_id,
            'giftName'=>$this->giftName,
            'giftUrl'=>$this->giftUrl,
            'userImageUrl'=>$this->userImageUrl,
            'islike'=>likeRepository::check($this->gift_id,$request->id),
            'gift_like'=>$this->gift_like,
            'giftuser_created_at'=>$this->giftuser_created_at,
            'name'=>$this->name,
            'family'=>$this->family,
            'userImageUrl'=>$this->userImageUrl,
        ];
    }
}
