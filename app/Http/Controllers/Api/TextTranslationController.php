<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TextTranslationResource;
use App\Models\TextTranslation;
use Illuminate\Http\Request;

class TextTranslationController extends Controller
{
    public function index(Request $request)
    {
        $query = TextTranslation::with('vocabulary');

        if ($request->has('vocabulary_id')) {
            $query->where('vocabulary_id', $request->vocabulary_id);
        }

        if ($request->has('source_language')) {
            $query->where('source_language', $request->source_language);
        }

        if ($request->has('target_language')) {
            $query->where('target_language', $request->target_language);
        }

        return TextTranslationResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vocabulary_id' => 'required|exists:sign_vocabularies,id',
            'source_language' => 'required|string|max:10',
            'target_language' => 'required|string|max:10',
            'source_text' => 'required|string',
            'translated_text' => 'required|string',
        ]);

        $translation = TextTranslation::create($validated);

        return new TextTranslationResource($translation->load('vocabulary'));
    }

    public function update(Request $request, TextTranslation $translation)
    {
        $validated = $request->validate([
            'source_text' => 'string',
            'translated_text' => 'string',
        ]);

        $translation->update($validated);

        return new TextTranslationResource($translation);
    }

    public function destroy(TextTranslation $translation)
    {
        $translation->delete();

        return response()->json(['message' => 'Translation deleted']);
    }
}
