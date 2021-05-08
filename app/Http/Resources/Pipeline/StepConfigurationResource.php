<?php

namespace App\Http\Resources\Pipeline;

use Illuminate\Http\Resources\Json\JsonResource;

class StepConfigurationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'details' => $this->details,
        ];
    }
}
