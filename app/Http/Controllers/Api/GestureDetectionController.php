<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetectGestureRequest;
use App\Models\TranslationHistory;
use App\Services\GestureDetectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class GestureDetectionController extends Controller
{
    public function __construct(
        private GestureDetectionService $detectionService
    ) {}

    /**
     * Detect sign language gesture from an uploaded image.
     *
     * POST /api/gestures/detect
     */
    public function detect(DetectGestureRequest $request): JsonResponse
    {
        try {
            $image = $request->file('image');

            $result = $this->detectionService->detect($image);

            // Optionally save to translation history
            $this->saveHistory($request, $result);

            if ($result['detected_letter']) {
                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'message' => 'Gesture berhasil dideteksi',
                ]);
            }

            return response()->json([
                'success' => false,
                'data' => [
                    'detected_letter' => null,
                    'confidence' => 0,
                ],
                'message' => 'Tidak dapat mendeteksi gesture dari gambar',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses gambar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save detection result to translation history.
     */
    private function saveHistory($request, array $result): void
    {
        try {
            TranslationHistory::create([
                'user_id' => $request->user()?->id,
                'session_id' => $request->header('X-Session-Id', Str::uuid()->toString()),
                'direction' => 'sign_to_text',
                'input_data' => 'image_upload',
                'output_data' => $result['detected_letter'],
                'input_language' => 'bisindo',
                'output_language' => 'id',
                'confidence_score' => $result['confidence'],
                'duration_seconds' => ($result['processing_time_ms'] ?? 0) / 1000,
            ]);
        } catch (\Exception $e) {
            // Don't fail the detection if history saving fails
            \Illuminate\Support\Facades\Log::warning('Failed to save translation history', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
