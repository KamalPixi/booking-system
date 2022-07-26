<?php

namespace App\Http\Resources\Agent;

use Illuminate\Http\Resources\Json\JsonResource;

class AirportResource extends JsonResource
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
            'id' => $this->code,
            'label' => $this->name,
            'desc' => $this->name,
            'value' => $this->code
        ];
    }
}
