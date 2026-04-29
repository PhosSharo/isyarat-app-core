<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HandGestureResource;
use App\Models\HandGesture;
use Illuminate\Http\Request;

class HandGestureController extends Controller
{
    public function index(Request $request)
    {
        $query = HandGesture::with('vocabulary');

        if ($request->has('vocabulary_id')) {
            $query->where('vocabulary_id', $request->vocabulary_id);
        }

        if ($request->has('gesture_type')) {
            $query->where('gesture_type', $request->gesture_type);
        }

        if ($request->has('source_dataset')) {
            $query->byDataset($request->source_dataset);
        }

        if ($request->has('hand')) {
            $query->where('hand', $request->hand);
        }

        return HandGestureResource::collection($query->latest()->get());
    }

    public function show(HandGesture $gesture)
    {
        $gesture->load('vocabulary');

        return new HandGestureResource($gesture);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vocabulary_id' => 'required|exists:sign_vocabularies,id',
            'landmarks' => 'required|array',
            'hand' => 'in:left,right,both',
            'gesture_type' => 'in:static,dynamic',
            'frame_count' => 'integer|min:1',
            'landmark_sequence' => 'nullable|array',
            'confidence_score' => 'nullable|numeric|min:0|max:1',
            'source_dataset' => 'nullable|string|max:255',
            'contributor_id' => 'nullable|string|max:255',
        ]);

        $gesture = HandGesture::create($validated);

        return new HandGestureResource($gesture->load('vocabulary'));
    }

    /**
     * Bulk import gestures (for ML pipeline / dataset seeding).
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'gestures' => 'required|array|min:1',
            'gestures.*.vocabulary_id' => 'required|exists:sign_vocabularies,id',
            'gestures.*.landmarks' => 'required|array',
            'gestures.*.hand' => 'in:left,right,both',
            'gestures.*.gesture_type' => 'in:static,dynamic',
            'gestures.*.frame_count' => 'integer|min:1',
            'gestures.*.landmark_sequence' => 'nullable|array',
            'gestures.*.confidence_score' => 'nullable|numeric|min:0|max:1',
            'gestures.*.source_dataset' => 'nullable|string|max:255',
            'gestures.*.contributor_id' => 'nullable|string|max:255',
        ]);

        $created = [];
        foreach ($validated['gestures'] as $gestureData) {
            $created[] = HandGesture::create($gestureData);
        }

        return response()->json([
            'message' => count($created) . ' gestures imported',
            'count' => count($created),
        ], 201);
    }

    public function destroy(HandGesture $gesture)
    {
        $gesture->delete();

        return response()->json(['message' => 'Gesture deleted']);
    }
}
