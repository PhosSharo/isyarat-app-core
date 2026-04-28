<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SignVocabulary extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'word',
        'description',
        'language',
        'type',
        'image_path',
        'video_path',
        'word_count',
        'character_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'word_count' => 'integer',
            'character_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SignVocabulary $vocab) {
            $vocab->character_count = mb_strlen($vocab->word);
            $vocab->word_count = str_word_count($vocab->word) ?: 1;
        });

        static::updating(function (SignVocabulary $vocab) {
            if ($vocab->isDirty('word')) {
                $vocab->character_count = mb_strlen($vocab->word);
                $vocab->word_count = str_word_count($vocab->word) ?: 1;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VocabularyCategory::class, 'category_id');
    }

    public function gestures(): HasMany
    {
        return $this->hasMany(HandGesture::class, 'vocabulary_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TextTranslation::class, 'vocabulary_id');
    }

    public function audioFiles(): HasMany
    {
        return $this->hasMany(AudioFile::class, 'vocabulary_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
