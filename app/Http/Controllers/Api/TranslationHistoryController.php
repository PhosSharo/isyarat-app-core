<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TranslationHistoryResource;
use App\Models\TranslationHistory;
use Illuminate\Http\Request;

class TranslationHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = TranslationHistory::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('session_id')) {
            $query->bySession($request->session_id);
        }

        if ($request->has('direction')) {
            $query->byDirection($request->direction);
        }

        $perPage = $request->integer('per_page', 50);

        return TranslationHistoryResource::collection(
            $query->latest()->paginate($perPage)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'session_id' => 'required|string|max:255',
            'direction' => 'required|in:sign_to_text,text_to_sign,speech_to_text,text_to_speech',
            'input_data' => 'nullable|string',
            'output_data' => 'nullable|string',
            'input_language' => 'required|in:bisindo,asl,id,en',
            'output_language' => 'required|in:bisindo,asl,id,en',
            'confidence_score' => 'nullable|numeric|min:0|max:1',
            'duration_seconds' => 'nullable|numeric|min:0',
            'is_correct' => 'nullable|boolean',
        ]);

        $history = TranslationHistory::create($validated);

        return new TranslationHistoryResource($history);
    }

    public function show(TranslationHistory $history)
    {
        $history->load('feedbacks');

        return new TranslationHistoryResource($history);
    }
}
