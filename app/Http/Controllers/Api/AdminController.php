<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\User;
use App\Services\PollaSettings;
use App\Services\ScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function users(): View
    {
        $users = User::query()
            ->withCount('predictions as total_predictions')
            ->orderByDesc('created_at')
            ->get(['id', 'username', 'role', 'has_paid', 'created_at']);

        return view('admin.users', compact('users'));
    }

    public function updatePayment(Request $request, User $user): RedirectResponse|JsonResponse
    {
        $data = $request->validate(['has_paid' => ['required', 'boolean']]);
        $user->update(['has_paid' => $data['has_paid']]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        $status = $data['has_paid'] ? 'habilitado' : 'deshabilitado';

        return redirect()->route('admin.users')->with('success', "Pago de {$user->username} {$status}.");
    }

    public function settings(PollaSettings $settings): View
    {
        return view('admin.settings', ['settings' => $settings->all()]);
    }

    public function saveSettings(Request $request, PollaSettings $settings): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'points_exact' => ['sometimes', 'integer', 'min:0', 'max:99'],
            'points_result' => ['sometimes', 'integer', 'min:0', 'max:99'],
            'deadline' => ['sometimes', 'nullable', 'date'],
        ]);

        foreach ($data as $key => $value) {
            $settings->upsert($key, $value ?? '');
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'settings' => $settings->all()]);
        }

        return redirect()->route('admin.settings')->with('success', 'Configuración guardada.');
    }

    public function saveResults(Request $request): RedirectResponse|JsonResponse
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

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.results')->with('success', 'Resultados guardados. No olvides recalcular los puntajes.');
    }

    public function calculate(Request $request, ScoringService $scoring): RedirectResponse|JsonResponse
    {
        $result = $scoring->calculate();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, ...$result]);
        }

        return redirect()->route('admin.results')->with('success', "Puntajes recalculados: {$result['updated']} pronósticos actualizados.");
    }

    public function results(): View
    {
        $matches = GameMatch::query()
            ->orderBy('group_name')
            ->orderBy('match_date')
            ->orderBy('id')
            ->get();

        return view('admin.results', compact('matches'));
    }

    public function resultsDetail(PollaController $polla): View
    {
        return view('polla.results', [
            ...$polla->resultsPayload(),
            'isAdmin' => true,
            'deadlinePassed' => true,
        ]);
    }
}
