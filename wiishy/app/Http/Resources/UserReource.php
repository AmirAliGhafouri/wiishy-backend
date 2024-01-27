<?php

namespace App\Http\Resources;

use App\Repositories\userRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReource extends JsonResource
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
            'name'=>$this->name,
            'family'=>$this->family,
            'user_image_url'=>$this->user_image_url,
            'age'=>userRepository::age($this->user_birthday),
        ];
    }
}
