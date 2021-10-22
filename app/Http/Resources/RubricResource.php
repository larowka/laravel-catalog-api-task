<?php

namespace App\Http\Resources;

use App\Models\Rubric;
use Illuminate\Http\Resources\Json\JsonResource;

class RubricResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subrubrics' => RubricResource::collection($this->whenLoaded('subrubrics')),
            'companies' => CompanyResource::collection($this->whenLoaded('companies')),
        ];
    }
}
