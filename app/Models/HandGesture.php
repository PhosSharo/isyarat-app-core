<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandGesture extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocabulary_id',
        'landmarks',
        'hand',
        'gesture_type',
        'frame_count',
        'landmark_sequence',
        'confidence_score',
        'source_dataset',
        'contributor_id',
    ];

    protected function casts(): array
    {
        return [
            'landmarks' => 'array',
            'landmark_sequence' => 'array',
            'confidence_score' => 'float',
            'frame_count' => 'integer',
        ];
    }

    public function vocabulary(): BelongsTo
    {
        return $this->belongsTo(SignVocabulary::class, 'vocabulary_id');
    }

    public function scopeStatic($query)
    {
        return $query->where('gesture_type', 'static');
    }

    public function scopeDynamic($query)
    {
        return $query->where('gesture_type', 'dynamic');
    }

    public function scopeByDataset($query, string $dataset)
    {
        return $query->where('source_dataset', $dataset);
    }
}
