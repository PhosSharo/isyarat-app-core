<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFeedbackResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'translation_history_id' => $this->translation_history_id,
            'ai_model_id' => $this->ai_model_id,
            'type' => $this->type,
            'is_correct' => $this->is_correct,
            'expected_output' => $this->expected_output,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
