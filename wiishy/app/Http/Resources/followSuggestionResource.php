<?php

namespace App\Http\Resources;

use App\Repositories\userRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class followSuggestionResource extends JsonResource
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
            "user_id"=> $this->id,
            "name"=> $this->name,
            "family"=> $this->family,
            "age"=> userRepository::age($this->user_birthday),
            "user_gender"=> $this->user_gender,
            "user_desc"=> $this->user_desc,
            "user_image_url"=> $this->user_image_url,
        ];
    }
}
