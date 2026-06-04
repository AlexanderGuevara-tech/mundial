<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\Prediction;
use App\Services\PollaSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PollaController extends Controller
{
    public function groups(): View
    {
        $groups = GameMatch::query()->orderBy('group_name')->orderBy('match_date')->orderBy('id')->get()->groupBy('group_name');

        return view('polla.groups', compact('groups'));
    }

    public function settings(PollaSettings $settings): JsonResponse
    {
        return response()->json([...$settings->all(), 'deadlinePassed' => $settings->deadlinePassed()]);
    }

    public function predictions(Request $request): View
    {
        $groups = GameMatch::query()->orderBy('group_name')->orderBy('match_date')->orderBy('id')->get()->groupBy('group_name');
        $settings = app(PollaSettings::class);

        $items = Prediction::query()->where('user_id', $request->user()->id)->get();
        $predictions = [];

        foreach ($items as $prediction) {
            $predictions[$prediction->match_id] = $prediction->only(['score_home', 'score_away']);
        }

        return view('polla.predictions', [
            'groups' => $groups,
            'predictions' => $predictions,
            'deadlinePassed' => $settings->deadlinePassed(),
            'hasPaid' => $request->user()->has_paid,
        ]);
    }

    public function savePredictions(Request $request, PollaSettings $settings): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'predictions' => ['required', 'array'],
            'predictions.*.match_id' => ['required', 'integer', 'exists:matches,id'],
            'predictions.*.score_home' => ['required', 'integer', 'min:0', 'max:99'],
            'predictions.*.score_away' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        if ($settings->deadlinePassed()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'El plazo para predicciones ha vencido.'], 403);
            }

            return back()->with('error', 'El plazo para predicciones ha vencido.');
        }

        if (! $request->user()->has_paid) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Debes estar habilitado (pago confirmado) para guardar predicciones.'], 403);
            }

            return back()->with('error', 'Debes estar habilitado para guardar predicciones.');
        }

        DB::transaction(function () use ($request, $data): void {
            foreach ($data['predictions'] as $prediction) {
                Prediction::query()->updateOrCreate(
                    ['user_id' => $request->user()->id, 'match_id' => $prediction['match_id']],
                    ['score_home' => $prediction['score_home'], 'score_away' => $prediction['score_away']]
                );
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'saved' => count($data['predictions'])]);
        }

        return redirect()->route('predictions')->with('success', 'Pronósticos guardados correctamente.');
    }

    public function leaderboard(): View
    {
        $users = DB::table('users')
            ->leftJoin('predictions', 'predictions.user_id', '=', 'users.id')
            ->where('users.role', '!=', 'admin')
            ->groupBy('users.id', 'users.username', 'users.has_paid')
            ->orderByDesc(DB::raw('COALESCE(SUM(predictions.points), 0)'))
            ->orderByDesc(DB::raw('COUNT(predictions.id)'))
            ->get([
                'users.id',
                'users.username',
                'users.has_paid',
                DB::raw('COALESCE(SUM(predictions.points), 0) as total_points'),
                DB::raw('COUNT(predictions.id) as total_predictions'),
            ]);

        return view('polla.leaderboard', compact('users'));
    }

    public function results(Request $request, PollaSettings $settings): View|JsonResponse
    {
        $deadlinePassed = $settings->deadlinePassed();
        $isAdmin = $request->user()->isAdmin();

        if (! $isAdmin && ! $deadlinePassed) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Los resultados estarán disponibles después de la fecha límite.'], 403);
            }
        }

        $payload = $this->resultsPayload();

        return view('polla.results', [
            ...$payload,
            'isAdmin' => $isAdmin,
            'deadlinePassed' => $deadlinePassed,
        ]);
    }

    public function resultsPayload(): array
    {
        $matches = GameMatch::query()->orderBy('group_name')->orderBy('match_date')->orderBy('id')->get();
        $users = DB::table('users')
            ->leftJoin('predictions', 'predictions.user_id', '=', 'users.id')
            ->where('users.role', '!=', 'admin')
            ->groupBy('users.id', 'users.username')
            ->orderByDesc(DB::raw('COALESCE(SUM(predictions.points), 0)'))
            ->orderBy('users.username')
            ->get(['users.id', 'users.username', DB::raw('COALESCE(SUM(predictions.points), 0) as total_points')]);

        $predictions = [];
        Prediction::query()->with('user')->whereHas('user', fn ($query) => $query->where('role', '!=', 'admin'))->get()
            ->each(function (Prediction $prediction) use (&$predictions): void {
                $predictions[$prediction->user_id][$prediction->match_id] = $prediction->only(['score_home', 'score_away', 'points']);
            });

        return compact('matches', 'users', 'predictions');
    }
}
