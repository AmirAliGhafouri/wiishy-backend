<?php

namespace App\Http\Resources;

use App\Repositories\eventRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class event_followingbirthdayResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'family' => $this->family,
            'event_type_id' => $this->event_type,
            'event_type' => eventRepository::type($this->event_type),
            'remaining_days' => eventRepository::remaining_days($this->event_date),
            'event_date' => $this->event_date,
            'status' => $this->status,
        ];
    }
}
