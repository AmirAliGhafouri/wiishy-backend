<?php

namespace App\Http\Resources;

use App\Repositories\followRepository;
use App\Repositories\userRepository;
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
        return [
            'isfollow'=>followRepository::check($request->user()->id,$this->id),
            'user_id'=>$this->id,
            'user_image_url'=>$this->user_image_url,
            'name'=>$this->name,
            'family'=>$this->family,
            'user_status'=>$this->status,
            'age'=>userRepository::age($this->user_birthday),
        ];
    }
}
