<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\Prediction;
use Illuminate\Support\Facades\DB;

class ScoringService
{
    public function __construct(private readonly PollaSettings $settings) {}

    public function calculate(): array
    {
        $config = $this->settings->all();
        $matches = GameMatch::query()
            ->whereNotNull('result_home')
            ->whereNotNull('result_away')
            ->get()
            ->keyBy('id');

        $updated = 0;

        DB::transaction(function () use ($matches, $config, &$updated): void {
            Prediction::query()->update(['points' => 0]);

            Prediction::query()->whereIn('match_id', $matches->keys())->chunkById(200, function ($predictions) use ($matches, $config, &$updated): void {
                foreach ($predictions as $prediction) {
                    $match = $matches[$prediction->match_id] ?? null;
                    if (! $match) {
                        continue;
                    }

                    $points = 0;
                    if ($prediction->score_home === $match->result_home && $prediction->score_away === $match->result_away) {
                        $points = $config['points_exact'];
                    } elseif (($prediction->score_home <=> $prediction->score_away) === ($match->result_home <=> $match->result_away)) {
                        $points = $config['points_result'];
                    }

                    $prediction->update(['points' => $points]);
                    $updated++;
                }
            });
        });

        return ['updated' => $updated, 'rules' => ['ptsExact' => $config['points_exact'], 'ptsResult' => $config['points_result']]];
    }
}
