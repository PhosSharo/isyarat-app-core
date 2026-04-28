<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AiModelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'version' => $this->version,
            'type' => $this->type,
            'language' => $this->language,
            'file_path' => $this->file_path,
            'file_size_mb' => $this->file_size_mb,
            'accuracy_percent' => $this->accuracy_percent,
            'num_classes' => $this->num_classes,
            'training_config' => $this->training_config,
            'metrics' => $this->metrics,
            'training_samples' => $this->training_samples,
            'validation_samples' => $this->validation_samples,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'notes' => $this->notes,
            'feedback_accuracy' => $this->when($this->relationLoaded('feedbacks'), fn () => $this->feedback_accuracy),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
