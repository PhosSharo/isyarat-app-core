<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VocabularyCategoryResource;
use App\Models\VocabularyCategory;
use Illuminate\Http\Request;

class VocabularyCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = VocabularyCategory::query()
            ->withCount('vocabularies');

        if ($request->has('language')) {
            $query->byLanguage($request->language);
        }

        if ($request->boolean('active_only', true)) {
            $query->active();
        }

        $categories = $query->orderBy('sort_order')->get();

        return VocabularyCategoryResource::collection($categories);
    }

    public function show(VocabularyCategory $category)
    {
        $category->load('vocabularies')->loadCount('vocabularies');

        return new VocabularyCategoryResource($category);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:vocabulary_categories',
            'description' => 'nullable|string',
            'language' => 'required|in:bisindo,asl',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $category = VocabularyCategory::create($validated);

        return new VocabularyCategoryResource($category);
    }

    public function update(Request $request, VocabularyCategory $category)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'slug' => 'string|max:255|unique:vocabulary_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'language' => 'in:bisindo,asl',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return new VocabularyCategoryResource($category);
    }

    public function destroy(VocabularyCategory $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
