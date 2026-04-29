<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GestureDetectionService
{
    private string $mlServiceUrl;

    public function __construct()
    {
        $this->mlServiceUrl = config('services.ml.url', 'http://127.0.0.1:5000');
    }

    /**
     * Detect sign language gesture from an uploaded image.
     *
     * Sends the image to the external ML service (Python Flask/FastAPI)
     * for inference and returns the prediction result.
     *
     * @param  UploadedFile  $image
     * @return array{detected_letter: string|null, word: string|null, meaning: string|null, confidence: float, confidence_score: float, processing_time_ms: int}
     */
    public function detect(UploadedFile $image): array
    {
        $startTime = microtime(true);

        try {
            $response = Http::timeout(30)
                ->attach(
                    'image',
                    file_get_contents($image->getRealPath()),
                    $image->getClientOriginalName()
                )
                ->post($this->mlServiceUrl . '/predict');

            $processingTime = (int) round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'detected_letter'    => $data['letter'] ?? $data['detected_letter'] ?? null,
                    'word'               => $data['word'] ?? $data['letter'] ?? $data['detected_letter'] ?? null,
                    'meaning'            => $data['meaning'] ?? null,
                    'confidence'         => (float) ($data['confidence'] ?? 0),
                    'confidence_score'   => (float) ($data['confidence'] ?? 0),
                    'processing_time_ms' => $data['processing_time_ms'] ?? $processingTime,
                ];
            }

            Log::warning('ML service returned non-successful response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return $this->emptyResult($processingTime);

        } catch (\Exception $e) {
            $processingTime = (int) round((microtime(true) - $startTime) * 1000);

            Log::error('ML service request failed', [
                'error'   => $e->getMessage(),
                'url'     => $this->mlServiceUrl . '/predict',
            ]);

            throw $e;
        }
    }

    /**
     * Return an empty/default result when detection fails.
     *
     * @param  int  $processingTimeMs
     * @return array{detected_letter: null, word: null, meaning: null, confidence: float, confidence_score: float, processing_time_ms: int}
     */
    private function emptyResult(int $processingTimeMs = 0): array
    {
        return [
            'detected_letter'    => null,
            'word'               => null,
            'meaning'            => null,
            'confidence'         => 0,
            'confidence_score'   => 0,
            'processing_time_ms' => $processingTimeMs,
        ];
    }
}
