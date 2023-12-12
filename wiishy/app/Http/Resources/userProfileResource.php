<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\userRepository;

class userProfileResource extends JsonResource
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
            "id"=> $this->id,
            "name"=> $this->name,
            "family"=> $this->family,
            "email"=> $this->email,
            "user_birthday"=> $this->user_birthday,
            "age"=> userRepository::age($this->user_birthday),
            "user_location_id"=> $this->user_location_id,
            "user_gender"=> $this->user_gender,
            "user_desc"=> $this->user_desc,
            "user_image_url"=> $this->user_image_url,
            "provider_id"=> $this->provider_id,
            "status"=> $this->status,
            "user_code"=> $this->user_code,
            "producer"=>$this->producer,
            "followers"=> $this->followers,
            "followings"=> $this->followings,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
        ];
    }
}
