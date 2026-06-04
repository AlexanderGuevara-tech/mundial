@extends('layouts.app')

@section('title', 'Bitácora')

@section('content')
<div class="mb-8 border-b border-gray-200">
    <nav class="flex gap-6 -mb-px">
        <a href="{{ route('admin.settings') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.settings') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            ⚙️ Configuración
        </a>
        <a href="{{ route('admin.users') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.users') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            👥 Usuarios
        </a>
        <a href="{{ route('admin.results') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.results') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            🏆 Resultados
        </a>
        <a href="{{ route('admin.results.detail') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.results.detail') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            📊 Detalle
        </a>
        <a href="{{ route('admin.logs') }}"
            class="pb-3 text-sm font-medium border-b-2 {{ request()->routeIs('admin.logs') ? 'border-amber-500 text-amber-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            📋 Bitácora
        </a>
    </nav>
</div>

<div class="space-y-6">
    <h1 class="text-2xl font-bold">Bitácora de Actividades</h1>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                        <th class="text-left px-6 py-3">Fecha</th>
                        <th class="text-left px-6 py-3">Usuario</th>
                        <th class="text-left px-6 py-3">Acción</th>
                        <th class="text-left px-6 py-3">Detalle</th>
                        <th class="text-left px-6 py-3">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($entries as $entry)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 whitespace-nowrap text-gray-500 text-xs">
                                {{ $entry->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap font-medium">
                                {{ $entry->user?->username ?? '—' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @if (str_contains($entry->action, 'Guardó')) bg-green-100 text-green-800
                                    @elseif (str_contains($entry->action, 'Recalculó')) bg-amber-100 text-amber-800
                                    @elseif (str_contains($entry->action, 'Registró') || str_contains($entry->action, 'Cambió')) bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-700
                                    @endif
                                ">
                                    {{ $entry->action }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-600 max-w-xs truncate">
                                {{ $entry->details }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-400 text-xs">
                                {{ $entry->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No hay actividades registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-sm text-gray-500">
        {{ $entries->links() }}
    </div>
</div>
@endsection
