<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AiModelResource;
use App\Models\AiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AiModelController extends Controller
{
    public function index(Request $request)
    {
        $query = AiModel::query();

        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('language')) {
            $query->byLanguage($request->language);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('deployed_only')) {
            $query->deployed();
        }

        return AiModelResource::collection($query->latest()->get());
    }

    public function show(AiModel $model)
    {
        $model->load('feedbacks');

        return new AiModelResource($model);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'type' => 'required|in:alphabet_classifier,word_classifier,stt_model,tts_model',
            'language' => 'required|in:bisindo,asl,id,en,universal',
            'file_path' => 'nullable|string',
            'file_size_mb' => 'nullable|numeric|min:0',
            'accuracy_percent' => 'nullable|numeric|min:0|max:100',
            'num_classes' => 'nullable|integer|min:1',
            'training_config' => 'nullable|array',
            'metrics' => 'nullable|array',
            'training_samples' => 'nullable|integer|min:0',
            'validation_samples' => 'nullable|integer|min:0',
            'status' => 'in:training,validating,ready,deployed,archived',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $model = AiModel::create($validated);

        return new AiModelResource($model);
    }

    public function update(Request $request, AiModel $model)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'version' => 'string|max:50',
            'type' => 'in:alphabet_classifier,word_classifier,stt_model,tts_model',
            'language' => 'in:bisindo,asl,id,en,universal',
            'file_path' => 'nullable|string',
            'file_size_mb' => 'nullable|numeric|min:0',
            'accuracy_percent' => 'nullable|numeric|min:0|max:100',
            'num_classes' => 'nullable|integer|min:1',
            'training_config' => 'nullable|array',
            'metrics' => 'nullable|array',
            'training_samples' => 'nullable|integer|min:0',
            'validation_samples' => 'nullable|integer|min:0',
            'status' => 'in:training,validating,ready,deployed,archived',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $model->update($validated);

        return new AiModelResource($model);
    }

    /**
     * Upload a TFLite model file.
     */
    public function uploadModel(Request $request, AiModel $model)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
        ]);

        $file = $request->file('file');
        $path = $file->storeAs(
            'models',
            $model->name . '_v' . $model->version . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        $model->update([
            'file_path' => $path,
            'file_size_mb' => round($file->getSize() / (1024 * 1024), 4),
        ]);

        return new AiModelResource($model);
    }

    /**
     * Deploy a model (set as active, deactivate others of same type+language).
     */
    public function deploy(AiModel $model)
    {
        // Deactivate other models of same type and language
        AiModel::where('type', $model->type)
            ->where('language', $model->language)
            ->where('id', '!=', $model->id)
            ->update(['is_active' => false, 'status' => 'archived']);

        $model->update([
            'status' => 'deployed',
            'is_active' => true,
        ]);

        return new AiModelResource($model);
    }

    public function destroy(AiModel $model)
    {
        if ($model->file_path) {
            Storage::disk('public')->delete($model->file_path);
        }

        $model->delete();

        return response()->json(['message' => 'Model deleted']);
    }
}
