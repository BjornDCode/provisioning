<?php

namespace App\Http\Resources\Pipeline;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Pipeline\StepConfigurationResource;

class StepResource extends JsonResource
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
            'title' => $this->title,
            'status' => $this->status,
            'config' => new StepConfigurationResource($this->config),
        ];
    }
}
