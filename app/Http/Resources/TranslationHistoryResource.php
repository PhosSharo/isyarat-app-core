<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'session_id' => $this->session_id,
            'direction' => $this->direction,
            'input_data' => $this->input_data,
            'output_data' => $this->output_data,
            'input_language' => $this->input_language,
            'output_language' => $this->output_language,
            'confidence_score' => $this->confidence_score,
            'duration_seconds' => $this->duration_seconds,
            'is_correct' => $this->is_correct,
            'feedbacks' => UserFeedbackResource::collection($this->whenLoaded('feedbacks')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
