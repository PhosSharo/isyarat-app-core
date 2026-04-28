<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HandGestureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vocabulary_id' => $this->vocabulary_id,
            'landmarks' => $this->landmarks,
            'hand' => $this->hand,
            'gesture_type' => $this->gesture_type,
            'frame_count' => $this->frame_count,
            'landmark_sequence' => $this->landmark_sequence,
            'confidence_score' => $this->confidence_score,
            'source_dataset' => $this->source_dataset,
            'contributor_id' => $this->contributor_id,
            'vocabulary' => new SignVocabularyResource($this->whenLoaded('vocabulary')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
