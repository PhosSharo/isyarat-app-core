<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VocabularyCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'language' => $this->language,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'vocabularies_count' => $this->whenCounted('vocabularies'),
            'vocabularies' => SignVocabularyResource::collection($this->whenLoaded('vocabularies')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
