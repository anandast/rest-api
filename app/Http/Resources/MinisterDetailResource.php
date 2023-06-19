<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MinisterDetailResource extends JsonResource
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
            'position' => $this->position,
            'name' => $this->name,
            'image' => asset('storage/' . $this->image),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->whenLoaded('status'),
            'ministry' => $this->whenLoaded('ministry'),
            'party' => $this->whenLoaded('party'),
            'category' => $this->whenLoaded('category')
        ];
    }
}
