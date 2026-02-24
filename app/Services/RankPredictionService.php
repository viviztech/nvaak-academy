<?php

namespace App\Services;

class RankPredictionService
{
    // NEET 2024 score-rank reference bands (approximate)
    private array $bands = [
        [720, 700, 1, 1000],
        [699, 680, 1001, 3000],
        [679, 650, 3001, 8000],
        [649, 620, 8001, 15000],
        [619, 590, 15001, 25000],
        [589, 560, 25001, 40000],
        [559, 530, 40001, 60000],
        [529, 500, 60001, 85000],
        [499, 470, 85001, 120000],
        [469, 440, 120001, 170000],
        [439, 400, 170001, 250000],
        [399, 350, 250001, 400000],
        [349, 300, 400001, 600000],
        [299, 200, 600001, 1000000],
        [199, 0, 1000001, 2000000],
    ];

    public function predictNeetRank(float $marks): array
    {
        foreach ($this->bands as $b) {
            if ($marks >= $b[1] && $marks <= $b[0]) {
                return [
                    'rank_from' => $b[2],
                    'rank_to'   => $b[3],
                    'label'     => 'Between ' . number_format($b[2]) . ' - ' . number_format($b[3]),
                ];
            }
        }

        return ['rank_from' => null, 'rank_to' => null, 'label' => 'Insufficient data'];
    }

    public function predictNeetScore(float $avgPercentage, float $totalMarks = 720): int
    {
        return (int) round($avgPercentage / 100 * $totalMarks);
    }
}
