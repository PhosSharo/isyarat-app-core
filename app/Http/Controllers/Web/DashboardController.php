<?php

namespace App\Http\Controllers\Web;

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
    public function index()
    {
        // Stats for overview cards
        $stats = [
            [
                'title'  => '1. Data Gestur Tangan',
                'satuan' => 'Jumlah gestur (buah/item)',
                'rows'   => [
                    'Total Gestur'   => HandGesture::count(),
                    'Gestur Statis'  => HandGesture::where('gesture_type', 'static')->count(),
                    'Gestur Dinamis' => HandGesture::where('gesture_type', 'dynamic')->count(),
                ],
            ],
            [
                'title'  => '2. Data Pengguna',
                'satuan' => 'Jumlah pengguna (orang)',
                'rows'   => [
                    'Total Pengguna'  => User::count(),
                    'Pengguna Aktif'  => User::where('is_active', true)->count(),
                ],
            ],
            [
                'title'  => '3. Data Kosakata Bahasa Isyarat',
                'satuan' => 'Jumlah kata (kata/item)',
                'rows'   => [
                    'Total Kosakata'   => SignVocabulary::count(),
                    'Kosakata BISINDO' => SignVocabulary::where('language', 'bisindo')->count(),
                    'Kosakata ASL'     => SignVocabulary::where('language', 'asl')->count(),
                ],
            ],
            [
                'title'  => '4. Data Terjemahan Teks',
                'satuan' => 'Jumlah karakter / kata (string)',
                'rows'   => [
                    'Total Terjemahan' => TextTranslation::count(),
                    'Total Karakter'   => TextTranslation::sum('character_count'),
                ],
            ],
            [
                'title'  => '5. Data Audio/Suara',
                'satuan' => 'Durasi (detik) / Ukuran file (MB)',
                'rows'   => [
                    'Total File'        => AudioFile::count(),
                    'Total Durasi (s)'  => round(AudioFile::sum('duration_seconds'), 2),
                    'Total Ukuran (MB)' => round(AudioFile::sum('file_size_mb'), 4),
                ],
            ],
            [
                'title'  => '6. Data Kategori Kosakata',
                'satuan' => 'Jumlah kategori (kategori)',
                'rows'   => [
                    'Total Kategori' => VocabularyCategory::count(),
                    'Kategori Aktif' => VocabularyCategory::where('is_active', true)->count(),
                ],
            ],
            [
                'title'  => '7. Data Riwayat Terjemahan',
                'satuan' => 'Jumlah sesi (sesi/log)',
                'rows'   => [
                    'Total Sesi'  => TranslationHistory::distinct('session_id')->count('session_id'),
                    'Total Entri' => TranslationHistory::count(),
                ],
            ],
            [
                'title'  => '8. Data Model AI/ML',
                'satuan' => 'Tingkat akurasi (%)',
                'rows'   => [
                    'Total Model'    => AiModel::count(),
                    'Model Deployed' => AiModel::where('status', 'deployed')->where('is_active', true)->count(),
                ],
            ],
            [
                'title'  => '9. Data Feedback Pengguna',
                'satuan' => 'Jumlah respons (benar/salah)',
                'rows'   => [
                    'Total Feedback' => UserFeedback::count(),
                    'Respons Benar'  => UserFeedback::where('is_correct', true)->count(),
                    'Respons Salah'  => UserFeedback::where('is_correct', false)->count(),
                ],
            ],
        ];

        // Table data for each of the 9 sections
        $gestures      = HandGesture::with('vocabulary:id,word')->orderBy('id')->paginate(20, ['*'], 'gestures_page');
        $users         = User::withCount(['translationHistories', 'feedbacks'])->orderBy('id')->paginate(20, ['*'], 'users_page');
        $vocabularies  = SignVocabulary::with('category:id,name')->withCount('gestures')->orderBy('id')->paginate(20, ['*'], 'vocabularies_page');
        $translations  = TextTranslation::with('vocabulary:id,word')->orderBy('id')->paginate(20, ['*'], 'translations_page');
        $audioFiles    = AudioFile::with('vocabulary:id,word')->orderBy('id')->paginate(20, ['*'], 'audio_page');
        $categories    = VocabularyCategory::withCount('vocabularies')->orderBy('id')->paginate(20, ['*'], 'categories_page');
        $histories     = TranslationHistory::orderBy('id')->paginate(20, ['*'], 'history_page');
        $models        = AiModel::withCount('feedbacks')->orderBy('id')->paginate(20, ['*'], 'models_page');
        $feedbacks     = UserFeedback::orderBy('id')->paginate(20, ['*'], 'feedbacks_page');

        return view('dashboard', compact(
            'stats',
            'gestures', 'users', 'vocabularies', 'translations',
            'audioFiles', 'categories', 'histories', 'models', 'feedbacks'
        ));
    }
}
