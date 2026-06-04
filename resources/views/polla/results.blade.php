@extends('layouts.app')

@section('title', 'Resultados')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Resultados</h1>

    @if (!$deadlinePassed && !$isAdmin)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-6 py-4 text-sm">
            Los resultados estarán disponibles después de la fecha límite.
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                            <th class="text-left px-3 py-3 whitespace-nowrap">Usuario</th>
                            @foreach ($matches as $match)
                                <th class="text-center px-2 py-3 whitespace-nowrap" title="{{ $match->team_home }} vs {{ $match->team_away }}">
                                    <div class="text-xs">{{ $match->team_home }}</div>
                                    <div class="text-xs text-gray-400">vs</div>
                                    <div class="text-xs">{{ $match->team_away }}</div>
                                </th>
                            @endforeach
                            <th class="text-center px-3 py-3 whitespace-nowrap">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-3 py-2 font-medium whitespace-nowrap">{{ $user->username }}</td>
                                @foreach ($matches as $match)
                                    @php
                                        $pred = $predictions[$user->id][$match->id] ?? null;
                                    @endphp
                                    <td class="px-2 py-2 text-center whitespace-nowrap">
                                        @if ($pred)
                                            <span class="inline-flex items-center gap-1">
                                                <span class="font-medium">{{ $pred['score_home'] }}-{{ $pred['score_away'] }}</span>
                                                @if ($pred['points'] > 0)
                                                    <span class="text-xs font-bold text-green-600">+{{ $pred['points'] }}</span>
                                                @elseif ($pred['points'] === 0 && $match->hasResult())
                                                    <span class="text-xs text-gray-400">0</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-3 py-2 text-center font-bold text-indigo-700">
                                    {{ $user->total_points }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-semibold">
                            <td class="px-3 py-2 whitespace-nowrap text-gray-600">Resultado</td>
                            @foreach ($matches as $match)
                                <td class="px-2 py-2 text-center whitespace-nowrap">
                                    @if ($match->hasResult())
                                        <span class="text-indigo-700">{{ $match->result_home }}-{{ $match->result_away }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            @endforeach
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
