<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TranslationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'direction',
        'input_data',
        'output_data',
        'input_language',
        'output_language',
        'confidence_score',
        'duration_seconds',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'float',
            'duration_seconds' => 'float',
            'is_correct' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(UserFeedback::class);
    }

    public function scopeBySession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeByDirection($query, string $direction)
    {
        return $query->where('direction', $direction);
    }
}
