<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SignVocabularyResource;
use App\Models\SignVocabulary;
use Illuminate\Http\Request;

class SignVocabularyController extends Controller
{
    public function index(Request $request)
    {
        $query = SignVocabulary::with('category')
            ->withCount('gestures');

        if ($request->has('language')) {
            $query->byLanguage($request->language);
        }

        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('word', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('active_only', true)) {
            $query->active();
        }

        return SignVocabularyResource::collection($query->orderBy('word')->get());
    }

    public function show(SignVocabulary $vocabulary)
    {
        $vocabulary->load(['category', 'gestures', 'translations', 'audioFiles']);

        return new SignVocabularyResource($vocabulary);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:vocabulary_categories,id',
            'word' => 'required|string|max:255',
            'description' => 'nullable|string',
            'language' => 'required|in:bisindo,asl',
            'type' => 'required|in:alphabet,number,word,phrase',
            'image_path' => 'nullable|string',
            'video_path' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $vocabulary = SignVocabulary::create($validated);

        return new SignVocabularyResource($vocabulary->load('category'));
    }

    public function update(Request $request, SignVocabulary $vocabulary)
    {
        $validated = $request->validate([
            'category_id' => 'exists:vocabulary_categories,id',
            'word' => 'string|max:255',
            'description' => 'nullable|string',
            'language' => 'in:bisindo,asl',
            'type' => 'in:alphabet,number,word,phrase',
            'image_path' => 'nullable|string',
            'video_path' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $vocabulary->update($validated);

        return new SignVocabularyResource($vocabulary->load('category'));
    }

    public function destroy(SignVocabulary $vocabulary)
    {
        $vocabulary->delete();

        return response()->json(['message' => 'Vocabulary deleted']);
    }
}
