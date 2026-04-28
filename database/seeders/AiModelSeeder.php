<?php

namespace Database\Seeders;

use App\Models\AiModel;
use Illuminate\Database\Seeder;

class AiModelSeeder extends Seeder
{
    public function run(): void
    {
        $models = [
            [
                'name' => 'asl_alphabet',
                'version' => '0.1.0',
                'type' => 'alphabet_classifier',
                'language' => 'asl',
                'accuracy_percent' => null,
                'num_classes' => 29,
                'training_config' => [
                    'architecture' => 'Dense NN',
                    'input_dim' => 126,
                    'layers' => [
                        'Dense(256, ReLU) + BN + Dropout(0.3)',
                        'Dense(128, ReLU) + BN + Dropout(0.3)',
                        'Dense(64, ReLU) + BN + Dropout(0.2)',
                    ],
                    'output' => 'Dense(29, Softmax)',
                    'dataset' => 'asl_alphabet_kaggle_87k',
                    'confidence_threshold' => 0.7,
                ],
                'status' => 'training',
                'is_active' => false,
                'notes' => 'Phase 1: ASL alphabet classifier. 29 classes (A-Z + space/del/nothing). Target >95% accuracy.',
            ],
            [
                'name' => 'bisindo_alphabet',
                'version' => '0.1.0',
                'type' => 'alphabet_classifier',
                'language' => 'bisindo',
                'accuracy_percent' => null,
                'num_classes' => 36,
                'training_config' => [
                    'architecture' => 'Dense NN (transfer from ASL)',
                    'input_dim' => 126,
                    'dataset' => 'roboflow_bisindov2',
                    'augmentation' => ['rotation', 'shear', 'scale', 'gaussian_noise'],
                    'confidence_threshold' => 0.7,
                ],
                'status' => 'training',
                'is_active' => false,
                'notes' => 'Phase 2: BISINDO alphabet classifier. 36 classes (A-Z + 0-9). Transfer learning from ASL model. Target >90%.',
            ],
            [
                'name' => 'asl_words',
                'version' => '0.1.0',
                'type' => 'word_classifier',
                'language' => 'asl',
                'accuracy_percent' => null,
                'num_classes' => 100,
                'training_config' => [
                    'architecture' => 'Siformer-lite',
                    'encoders' => 3,
                    'encoder_layers' => 2,
                    'decoder_layers' => 1,
                    'attention_heads' => 4,
                    'input_frames' => 60,
                    'dataset' => 'wlasl_top100',
                ],
                'status' => 'training',
                'is_active' => false,
                'notes' => 'Phase 3: ASL word-level classifier. Siformer-lite on WLASL top-100. Target >85% signer-dependent.',
            ],
            [
                'name' => 'bisindo_words',
                'version' => '0.1.0',
                'type' => 'word_classifier',
                'language' => 'bisindo',
                'accuracy_percent' => null,
                'num_classes' => 32,
                'training_config' => [
                    'architecture' => 'Siformer-lite (transfer from ASL)',
                    'dataset' => 'wl_bisindo_32words',
                    'signers' => 5,
                    'license' => 'CC BY-NC 4.0',
                ],
                'status' => 'training',
                'is_active' => false,
                'notes' => 'Phase 4: BISINDO word-level classifier. WL-BISINDO 32 words. Target >80% signer-dependent. CC BY-NC 4.0 license.',
            ],
        ];

        foreach ($models as $model) {
            AiModel::updateOrCreate(
                ['name' => $model['name'], 'version' => $model['version']],
                $model
            );
        }
    }
}
