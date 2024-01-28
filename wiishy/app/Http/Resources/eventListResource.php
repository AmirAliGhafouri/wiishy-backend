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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'family' => $this->family,
            'gender' => $this->gender,
            'relationship' => eventRepository::rel_type($this->relationship),
            'relationship_type' => $this->relationship,
            'wiishy_user_id' => $this->wiishy_user_id,
            'event_type_id' => $this->event_type,
            'event_type' => eventRepository::type($this->event_type),
            'remaining_days' => eventRepository::remaining_days($this->event_date),
            'event_date' => $this->event_date,
            'repeatable' => $this->repeatable,
            'status' => $this->status,
        ];
    }
}
