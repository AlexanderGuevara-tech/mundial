<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\User;
use App\Services\PollaSettings;
use App\Services\ScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function users(): JsonResponse
    {
        return response()->json(
            User::query()
                ->withCount('predictions as total_predictions')
                ->orderByDesc('created_at')
                ->get(['id', 'username', 'role', 'has_paid', 'created_at'])
        );
    }

    public function updatePayment(Request $request, User $user): JsonResponse
    {
        $data = $request->validate(['has_paid' => ['required', 'boolean']]);
        $user->update(['has_paid' => $data['has_paid']]);

        return response()->json(['success' => true]);
    }

    public function settings(PollaSettings $settings): JsonResponse
    {
        return response()->json($settings->all());
    }

    public function saveSettings(Request $request, PollaSettings $settings): JsonResponse
    {
        $data = $request->validate([
            'points_exact' => ['sometimes', 'integer', 'min:0', 'max:99'],
            'points_result' => ['sometimes', 'integer', 'min:0', 'max:99'],
            'deadline' => ['sometimes', 'nullable', 'date'],
        ]);

        foreach ($data as $key => $value) {
            $settings->upsert($key, $value ?? '');
        }

        return response()->json(['success' => true, 'settings' => $settings->all()]);
    }

    public function saveResults(Request $request): JsonResponse
    {
        $data = $request->validate([
            'results' => ['required', 'array'],
            'results.*.match_id' => ['required', 'integer', 'exists:matches,id'],
            'results.*.result_home' => ['required', 'integer', 'min:0', 'max:99'],
            'results.*.result_away' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        DB::transaction(function () use ($data): void {
            foreach ($data['results'] as $result) {
                GameMatch::query()->whereKey($result['match_id'])->update([
                    'result_home' => $result['result_home'],
                    'result_away' => $result['result_away'],
                ]);
            }
        });

        return response()->json(['success' => true]);
    }

    public function calculate(ScoringService $scoring): JsonResponse
    {
        return response()->json(['success' => true, ...$scoring->calculate()]);
    }

    public function resultsDetail(PollaController $polla): JsonResponse
    {
        return response()->json($polla->resultsPayload());
    }
}
