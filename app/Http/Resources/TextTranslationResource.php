<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TextTranslationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vocabulary_id' => $this->vocabulary_id,
            'source_language' => $this->source_language,
            'target_language' => $this->target_language,
            'source_text' => $this->source_text,
            'translated_text' => $this->translated_text,
            'character_count' => $this->character_count,
            'vocabulary' => new SignVocabularyResource($this->whenLoaded('vocabulary')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
