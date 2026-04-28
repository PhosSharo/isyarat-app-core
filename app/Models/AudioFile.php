<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudioFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocabulary_id',
        'file_path',
        'mime_type',
        'duration_seconds',
        'file_size_mb',
        'language',
        'transcript',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'float',
            'file_size_mb' => 'float',
        ];
    }

    public function vocabulary(): BelongsTo
    {
        return $this->belongsTo(SignVocabulary::class, 'vocabulary_id');
    }
}
