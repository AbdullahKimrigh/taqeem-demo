<?php

namespace App\Services;

use App\Models\DamageCase;

/**
 * Simulated AI damage analyzer. Returns realistic structured damage data
 * based on rule-based / random logic for demo purposes.
 */
class DamageAnalyzerService
{
    private static array $partNames = [
        'Front Bumper', 'Rear Bumper', 'Front Fender (L)', 'Front Fender (R)',
        'Rear Fender (L)', 'Rear Fender (R)', 'Hood', 'Trunk Door', 'Front Door (L)',
        'Front Door (R)', 'Rear Door (L)', 'Rear Door (R)', 'Headlight (L)', 'Headlight (R)',
        'Tail Light (L)', 'Tail Light (R)', 'Windshield', 'Side Mirror (L)', 'Side Mirror (R)',
        'Roof', 'Quarter Panel (L)', 'Quarter Panel (R)',
    ];

    private static array $actions = [
        ['type' => 'Replace', 'cost_min' => 800, 'cost_max' => 3500],
        ['type' => 'Repair + Paint', 'cost_min' => 300, 'cost_max' => 1200],
        ['type' => 'Repair', 'cost_min' => 150, 'cost_max' => 600],
        ['type' => 'Paint Only', 'cost_min' => 200, 'cost_max' => 800],
    ];

    public function analyze(DamageCase $case): array
    {
        $numDamages = random_int(2, 6);
        $usedParts = [];
        $damages = [];
        $total = 0;

        while (count($damages) < $numDamages) {
            $part = self::$partNames[array_rand(self::$partNames)];
            if (in_array($part, $usedParts)) {
                continue;
            }
            $usedParts[] = $part;

            $actionSet = self::$actions[array_rand(self::$actions)];
            $cost = random_int($actionSet['cost_min'], $actionSet['cost_max']);
            $total += $cost;

            $damages[] = [
                'part' => $part,
                'action' => $actionSet['type'],
                'cost' => $cost,
            ];
        }

        $minRange = (int) ($total * 0.85);
        $maxRange = (int) ($total * 1.15);

        return [
            'damages' => $damages,
            'estimated_total' => $total,
            'cost_range' => [
                'min' => $minRange,
                'max' => $maxRange,
            ],
            'analyzed_at' => now()->toIso8601String(),
        ];
    }
}
