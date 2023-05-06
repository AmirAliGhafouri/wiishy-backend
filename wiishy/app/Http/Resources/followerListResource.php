<?php

namespace App\Http\Resources;

use App\Repositories\followRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class followerListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);
        return [
            'isfollow'=>followRepository::check($this->id,$this->user_id),
            'user_id'=>$this->user_id,
            'userImageUrl'=>$this->userImageUrl,
            'name'=>$this->name,
            'family'=>$this->family,
            'user_status'=>$this->user_status,
        ];
    }
}
