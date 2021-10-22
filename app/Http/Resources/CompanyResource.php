<?php

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'title' => $this->title,
            'building' => BuildingResource::make($this->whenLoaded('building')),
            'rubrics' => RubricResource::collection($this->whenLoaded('rubrics')),
            'distance' => $this->when(isset($this->distance), $this->distance),
            'coords' => $this->when(isset($this->latitude, $this->longitude), [
                'lat' => $this->latitude,
                'lon' => $this->longitude
            ])
        ];
    }

}
