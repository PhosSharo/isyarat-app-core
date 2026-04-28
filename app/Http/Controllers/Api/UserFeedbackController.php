<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserFeedbackResource;
use App\Models\UserFeedback;
use Illuminate\Http\Request;

class UserFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = UserFeedback::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('ai_model_id')) {
            $query->where('ai_model_id', $request->ai_model_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('is_correct')) {
            $query->where('is_correct', $request->boolean('is_correct'));
        }

        $perPage = $request->integer('per_page', 50);

        return UserFeedbackResource::collection($query->latest()->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'translation_history_id' => 'nullable|exists:translation_histories,id',
            'ai_model_id' => 'nullable|exists:ai_models,id',
            'type' => 'required|in:correction,rating,bug_report,suggestion',
            'is_correct' => 'nullable|boolean',
            'expected_output' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'metadata' => 'nullable|array',
        ]);

        $feedback = UserFeedback::create($validated);

        return new UserFeedbackResource($feedback);
    }

    public function show(UserFeedback $feedback)
    {
        return new UserFeedbackResource($feedback);
    }
}
