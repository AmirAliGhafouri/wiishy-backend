<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\eventRepository;

class eventListResource extends JsonResource
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
            'gender'=>$this->gender,
            'relationship'=>$this->relationship,
            'event_type'=>eventRepository::type($this->id),
            'event_date'=>$this->event_date,
            'status'=>$this->status,
        ];
    }
}
