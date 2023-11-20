<?php

namespace App\Http\Resources;

use App\Repositories\likeRepository;
use App\Repositories\userRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class exploreResource extends JsonResource
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
            'gift_id'=>$this->id,
            'gift_name'=>$this->gift_name,
            'gift_url'=>$this->gift_url,
            'gift_image_url'=>$this->gift_image_url,
            'islike'=>likeRepository::check($this->id,$request->user()->id),
            'gift_like'=>$this->gift_like,
            'gifts_created_at'=>$this->gifts_created_at,
            'user'=>userRepository::details($this->user_id),
        ];
    }
}
