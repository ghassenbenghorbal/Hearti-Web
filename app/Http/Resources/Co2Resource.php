<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Co2Resource extends JsonResource
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
            'x' => $this->created_at->format("H:i:s"),
            'y' => $this->quantity
        ];
    }
}
