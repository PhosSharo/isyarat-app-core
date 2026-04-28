<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignVocabularyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'word' => $this->word,
            'description' => $this->description,
            'language' => $this->language,
            'type' => $this->type,
            'image_path' => $this->image_path,
            'video_path' => $this->video_path,
            'word_count' => $this->word_count,
            'character_count' => $this->character_count,
            'is_active' => $this->is_active,
            'category' => new VocabularyCategoryResource($this->whenLoaded('category')),
            'gestures_count' => $this->whenCounted('gestures'),
            'gestures' => HandGestureResource::collection($this->whenLoaded('gestures')),
            'translations' => TextTranslationResource::collection($this->whenLoaded('translations')),
            'audio_files' => AudioFileResource::collection($this->whenLoaded('audioFiles')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
