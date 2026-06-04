@extends('layouts.app')

@section('title', 'Tabla de Posiciones')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Tabla de Posiciones</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                        <th class="text-left px-6 py-3">#</th>
                        <th class="text-left px-6 py-3">Usuario</th>
                        <th class="text-center px-6 py-3">Estado</th>
                        <th class="text-center px-6 py-3">Puntos</th>
                        <th class="text-center px-6 py-3">Pronósticos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $index => $user)
                        <tr class="hover:bg-gray-50 transition {{ $index === 0 ? 'bg-amber-50' : '' }}">
                            <td class="px-6 py-4 font-bold text-gray-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($user->has_paid)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Habilitado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-indigo-700 text-lg">
                                {{ $user->total_points }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500">
                                {{ $user->total_predictions }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No hay usuarios en la tabla todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
