<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiModel;
use App\Models\AudioFile;
use App\Models\HandGesture;
use App\Models\SignVocabulary;
use App\Models\TextTranslation;
use App\Models\TranslationHistory;
use App\Models\User;
use App\Models\UserFeedback;
use App\Models\VocabularyCategory;

class DashboardController extends Controller
{
    /**
     * Overview stats matching all required variables and units.
     */
    public function stats()
    {
        // Data Gestur Tangan - Jumlah gestur (buah/item)
        $totalGestures = HandGesture::count();
        $staticGestures = HandGesture::static()->count();
        $dynamicGestures = HandGesture::dynamic()->count();

        // Data Pengguna - Jumlah pengguna (orang)
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();

        // Data Kosakata Bahasa Isyarat - Jumlah kata (kata/item)
        $totalVocabularies = SignVocabulary::count();
        $bisindoVocabularies = SignVocabulary::byLanguage('bisindo')->count();
        $aslVocabularies = SignVocabulary::byLanguage('asl')->count();

        // Data Terjemahan Teks - Jumlah karakter / kata (string)
        $totalTranslations = TextTranslation::count();
        $totalCharacters = TextTranslation::sum('character_count');

        // Data Audio/Suara - Durasi (detik) / Ukuran file (MB)
        $totalAudioFiles = AudioFile::count();
        $totalAudioDuration = AudioFile::sum('duration_seconds');
        $totalAudioSizeMb = AudioFile::sum('file_size_mb');

        // Data Kategori Kosakata - Jumlah kategori (kategori)
        $totalCategories = VocabularyCategory::count();
        $activeCategories = VocabularyCategory::active()->count();

        // Data Riwayat Terjemahan - Jumlah sesi (sesi/log)
        $totalHistorySessions = TranslationHistory::distinct('session_id')->count('session_id');
        $totalHistoryEntries = TranslationHistory::count();

        // Data Model AI/ML - Tingkat akurasi (%)
        $aiModels = AiModel::all()->map(fn ($m) => [
            'name' => $m->name,
            'version' => $m->version,
            'type' => $m->type,
            'language' => $m->language,
            'accuracy_percent' => $m->accuracy_percent,
            'status' => $m->status,
            'is_active' => $m->is_active,
        ]);
        $deployedModels = AiModel::deployed()->count();

        // Data Feedback Pengguna - Jumlah respons (benar/salah)
        $totalFeedbacks = UserFeedback::count();
        $correctResponses = UserFeedback::where('is_correct', true)->count();
        $incorrectResponses = UserFeedback::where('is_correct', false)->count();

        return response()->json([
            'data_gestur_tangan' => [
                'total_gestur' => $totalGestures,
                'gestur_statis' => $staticGestures,
                'gestur_dinamis' => $dynamicGestures,
                'satuan' => 'buah/item',
            ],
            'data_pengguna' => [
                'total_pengguna' => $totalUsers,
                'pengguna_aktif' => $activeUsers,
                'satuan' => 'orang',
            ],
            'data_kosakata_bahasa_isyarat' => [
                'total_kosakata' => $totalVocabularies,
                'kosakata_bisindo' => $bisindoVocabularies,
                'kosakata_asl' => $aslVocabularies,
                'satuan' => 'kata/item',
            ],
            'data_terjemahan_teks' => [
                'total_terjemahan' => $totalTranslations,
                'total_karakter' => $totalCharacters,
                'satuan' => 'karakter/kata (string)',
            ],
            'data_audio_suara' => [
                'total_file' => $totalAudioFiles,
                'total_durasi_detik' => round($totalAudioDuration, 2),
                'total_ukuran_mb' => round($totalAudioSizeMb, 4),
                'satuan' => 'detik / MB',
            ],
            'data_kategori_kosakata' => [
                'total_kategori' => $totalCategories,
                'kategori_aktif' => $activeCategories,
                'satuan' => 'kategori',
            ],
            'data_riwayat_terjemahan' => [
                'total_sesi' => $totalHistorySessions,
                'total_entri' => $totalHistoryEntries,
                'satuan' => 'sesi/log',
            ],
            'data_model_ai_ml' => [
                'total_model' => $aiModels->count(),
                'model_deployed' => $deployedModels,
                'models' => $aiModels,
                'satuan' => 'tingkat akurasi (%)',
            ],
            'data_feedback_pengguna' => [
                'total_feedback' => $totalFeedbacks,
                'respons_benar' => $correctResponses,
                'respons_salah' => $incorrectResponses,
                'satuan' => 'benar/salah',
            ],
        ]);
    }
}
