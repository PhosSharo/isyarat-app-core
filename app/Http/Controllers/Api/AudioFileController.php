<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AudioFileResource;
use App\Models\AudioFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioFileController extends Controller
{
    public function index(Request $request)
    {
        $query = AudioFile::with('vocabulary');

        if ($request->has('vocabulary_id')) {
            $query->where('vocabulary_id', $request->vocabulary_id);
        }

        if ($request->has('language')) {
            $query->where('language', $request->language);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return AudioFileResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vocabulary_id' => 'nullable|exists:sign_vocabularies,id',
            'file' => 'required|file|mimes:wav,mp3,ogg,m4a|max:10240',
            'language' => 'string|max:10',
            'transcript' => 'nullable|string',
            'type' => 'in:tts_output,stt_input,reference',
        ]);

        $file = $request->file('file');
        $path = $file->store('audio', 'public');
        $sizeInMb = round($file->getSize() / (1024 * 1024), 4);

        $audio = AudioFile::create([
            'vocabulary_id' => $validated['vocabulary_id'] ?? null,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'duration_seconds' => 0, // To be updated by client or processing job
            'file_size_mb' => $sizeInMb,
            'language' => $validated['language'] ?? 'id',
            'transcript' => $validated['transcript'] ?? null,
            'type' => $validated['type'] ?? 'reference',
        ]);

        return new AudioFileResource($audio);
    }

    public function show(AudioFile $audio)
    {
        $audio->load('vocabulary');

        return new AudioFileResource($audio);
    }

    public function destroy(AudioFile $audio)
    {
        if ($audio->file_path) {
            Storage::disk('public')->delete($audio->file_path);
        }

        $audio->delete();

        return response()->json(['message' => 'Audio file deleted']);
    }
}
