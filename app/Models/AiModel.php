<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'version',
        'type',
        'language',
        'file_path',
        'file_size_mb',
        'accuracy_percent',
        'num_classes',
        'training_config',
        'metrics',
        'training_samples',
        'validation_samples',
        'status',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'training_config' => 'array',
            'metrics' => 'array',
            'file_size_mb' => 'float',
            'accuracy_percent' => 'float',
            'num_classes' => 'integer',
            'training_samples' => 'integer',
            'validation_samples' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(UserFeedback::class, 'ai_model_id');
    }

    public function scopeDeployed($query)
    {
        return $query->where('status', 'deployed')->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Get accuracy rate from feedbacks (benar/salah).
     */
    public function getFeedbackAccuracyAttribute(): ?float
    {
        $feedbacks = $this->feedbacks()->whereNotNull('is_correct');
        $total = $feedbacks->count();
        if ($total === 0) return null;
        $correct = $feedbacks->where('is_correct', true)->count();
        return round(($correct / $total) * 100, 2);
    }
}
