<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AudioFileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vocabulary_id' => $this->vocabulary_id,
            'file_path' => $this->file_path,
            'mime_type' => $this->mime_type,
            'duration_seconds' => $this->duration_seconds,
            'file_size_mb' => $this->file_size_mb,
            'language' => $this->language,
            'transcript' => $this->transcript,
            'type' => $this->type,
            'vocabulary' => new SignVocabularyResource($this->whenLoaded('vocabulary')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
