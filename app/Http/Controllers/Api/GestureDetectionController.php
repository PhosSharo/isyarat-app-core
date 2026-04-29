<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetectGestureRequest;
use App\Services\GestureDetectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GestureDetectionController extends Controller
{
    public function __construct(
        private GestureDetectionService $detectionService
    ) {}

    /**
     * Detect sign language gesture from an uploaded image.
     *
     * Accepts a JPEG/PNG image (max 5MB) via multipart/form-data,
     * sends it to the ML service for inference, and returns the
     * detected letter/word with confidence score.
     *
     * Optionally saves the result to translation_histories if a
     * user is authenticated.
     *
     * @param  DetectGestureRequest  $request
     * @return JsonResponse
     */
    public function detect(DetectGestureRequest $request): JsonResponse
    {
        try {
            $image  = $request->file('image');
            $result = $this->detectionService->detect($image);

            // Optionally log to translation_histories if user is authenticated
            if ($result['detected_letter'] && $request->user()) {
                $this->saveToHistory($request, $result);
            }

            if ($result['detected_letter']) {
                return response()->json([
                    'success' => true,
                    'data'    => $result,
                    'message' => 'Gesture berhasil dideteksi',
                ]);
            }

            return response()->json([
                'success' => false,
                'data'    => [
                    'detected_letter'    => null,
                    'confidence'         => 0,
                    'confidence_score'   => 0,
                    'processing_time_ms' => $result['processing_time_ms'] ?? 0,
                ],
                'message' => 'Tidak dapat mendeteksi gesture dari gambar',
            ]);

        } catch (\Exception $e) {
            Log::error('Gesture detection failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses gambar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save detection result to translation_histories table.
     *
     * @param  DetectGestureRequest  $request
     * @param  array                 $result
     * @return void
     */
    private function saveToHistory(DetectGestureRequest $request, array $result): void
    {
        try {
            \App\Models\TranslationHistory::create([
                'user_id'          => $request->user()->id,
                'session_id'       => 'detect-' . now()->timestamp . '-' . uniqid(),
                'direction'        => 'sign_to_text',
                'input_data'       => $request->file('image')->getClientOriginalName(),
                'output_data'      => $result['detected_letter'],
                'input_language'   => 'bisindo',
                'output_language'  => 'id',
                'confidence_score' => $result['confidence_score'],
                'duration_seconds' => $result['processing_time_ms'] / 1000,
                'is_correct'       => null,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to save detection to history', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
