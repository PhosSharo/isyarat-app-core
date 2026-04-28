<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TextTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocabulary_id',
        'source_language',
        'target_language',
        'source_text',
        'translated_text',
        'character_count',
    ];

    protected function casts(): array
    {
        return [
            'character_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (TextTranslation $translation) {
            $translation->character_count = mb_strlen($translation->translated_text);
        });
    }

    public function vocabulary(): BelongsTo
    {
        return $this->belongsTo(SignVocabulary::class, 'vocabulary_id');
    }
}
