<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pdfUrl' => $this->pdfUrl,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'examdate' => new ExamdateResource($this->whenLoaded('examdate'))
        ];
    }
}
