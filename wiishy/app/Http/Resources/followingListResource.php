<?php

namespace App\Http\Resources;

use App\Repositories\followRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class followingListResource extends JsonResource
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
            'userImageUrl'=>$this->userImageUrl,
            'name'=>$this->name,
            'family'=>$this->family,
            'user_status'=>$this->user_status,
        ];
    }
}
