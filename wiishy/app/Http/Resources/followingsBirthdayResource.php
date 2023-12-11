<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class followingsBirthdayResource extends JsonResource
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
            'name'=>$this->name,
            'family'=>$this->family,
            'user_image_url'=>$this->user_image_url,
            'user_birthday'=>$this->user_birthday,
        ];
    }
}
