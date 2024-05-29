<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'name' => $this->name,
            'point' => $this->level->point,
            'isGraduate' => $this->is_graduate,
            'level' => $this->level,
            'category' => new CategoryResource($this -> category),
            'choices' => ChoiceResource::collection($this->whenLoaded('choices'))
        ];
    }
}
