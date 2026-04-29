<?php

use App\Http\Controllers\Api\AiModelController;
use App\Http\Controllers\Api\AudioFileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GestureDetectionController;
use App\Http\Controllers\Api\HandGestureController;
use App\Http\Controllers\Api\SignVocabularyController;
use App\Http\Controllers\Api\TextTranslationController;
use App\Http\Controllers\Api\TranslationHistoryController;
use App\Http\Controllers\Api\UserFeedbackController;
use App\Http\Controllers\Api\VocabularyCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes (no auth required for early-phase testing)
|--------------------------------------------------------------------------
*/

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Health check
Route::get('/health', fn () => response()->json([
    'status' => 'ok',
    'app' => config('app.name'),
    'timestamp' => now()->toIso8601String(),
]));

// Dashboard / Stats
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

// Vocabulary Categories
Route::apiResource('categories', VocabularyCategoryController::class);

// Sign Vocabularies (Dictionary)
Route::apiResource('vocabularies', SignVocabularyController::class);

// Hand Gestures
Route::apiResource('gestures', HandGestureController::class)->except(['update']);
Route::post('/gestures/bulk', [HandGestureController::class, 'bulkStore']);

// Gesture Detection (Sign Language Recognition)
Route::post('/gestures/detect', [GestureDetectionController::class, 'detect']);

// Text Translations
Route::apiResource('translations', TextTranslationController::class)->except(['show']);

// Audio Files
Route::apiResource('audio', AudioFileController::class)->except(['update']);

// Translation History
Route::apiResource('history', TranslationHistoryController::class)->only(['index', 'store', 'show']);

// AI/ML Models
Route::apiResource('models', AiModelController::class);
Route::post('/models/{model}/upload', [AiModelController::class, 'uploadModel']);
Route::post('/models/{model}/deploy', [AiModelController::class, 'deploy']);

// User Feedback
Route::apiResource('feedbacks', UserFeedbackController::class)->only(['index', 'store', 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (for future use)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',  [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
