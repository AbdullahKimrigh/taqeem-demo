<?php

namespace Database\Seeders;

use App\Models\DamageCase;
use App\Models\CaseImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DamageCaseSeeder extends Seeder
{
    public function run(): void
    {
        $tinyPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

        $cases = [
            [
                'accident_number' => 'ACC-2025-001',
                'plate_number' => 'ABC 1234',
                'status' => DamageCase::STATUS_COMPLETED,
                'service_fee_paid' => true,
                'evaluator_name' => 'Ahmad Al-Rashid',
                'ai_result' => [
                    'damages' => [
                        ['part' => 'Rear Bumper', 'action' => 'Replace', 'cost' => 1200],
                        ['part' => 'Trunk Door', 'action' => 'Repair + Paint', 'cost' => 850],
                        ['part' => 'Tail Light (R)', 'action' => 'Replace', 'cost' => 320],
                    ],
                    'estimated_total' => 2370,
                    'cost_range' => ['min' => 2015, 'max' => 2725],
                ],
                'final_result' => [
                    'damages' => [
                        ['part' => 'Rear Bumper', 'action' => 'Replace', 'cost' => 1200],
                        ['part' => 'Trunk Door', 'action' => 'Repair + Paint', 'cost' => 850],
                        ['part' => 'Tail Light (R)', 'action' => 'Replace', 'cost' => 320],
                    ],
                    'estimated_total' => 2370,
                ],
            ],
            [
                'accident_number' => 'ACC-2025-002',
                'plate_number' => 'XYZ 5678',
                'status' => DamageCase::STATUS_READY,
                'service_fee_paid' => true,
            ],
            [
                'accident_number' => 'ACC-2025-003',
                'plate_number' => 'KSA 9999',
                'status' => DamageCase::STATUS_SUBMITTED,
                'service_fee_paid' => false,
            ],
            [
                'accident_number' => 'ACC-2025-004',
                'plate_number' => 'DMM 1111',
                'status' => DamageCase::STATUS_NEEDS_IMAGES,
                'service_fee_paid' => true,
            ],
            [
                'accident_number' => 'ACC-2025-005',
                'plate_number' => 'Riyadh 5555',
                'status' => DamageCase::STATUS_ESCALATED,
                'service_fee_paid' => true,
            ],
        ];

        foreach ($cases as $data) {
            $aiResult = $data['ai_result'] ?? null;
            $finalResult = $data['final_result'] ?? null;
            unset($data['ai_result'], $data['final_result']);
            $case = DamageCase::create($data);
            if ($aiResult) {
                $case->update(['ai_result' => $aiResult]);
            }
            if ($finalResult) {
                $case->update(['final_result' => $finalResult]);
            }

            $dir = "cases/{$case->id}";
            Storage::disk('public')->makeDirectory($dir);
            for ($j = 0; $j < 6; $j++) {
                $path = "{$dir}/img-{$j}.png";
                Storage::disk('public')->put($path, $tinyPng);
                CaseImage::create(['case_id' => $case->id, 'path' => $path]);
            }
        }
    }
}
