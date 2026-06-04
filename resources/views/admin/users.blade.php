@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
{{-- Admin navigation tabs --}}
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
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Usuarios</h1>
        <a href="{{ route('admin.register') }}" class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
            + Nuevo Usuario
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                        <th class="text-left px-6 py-3">Usuario</th>
                        <th class="text-center px-6 py-3">Rol</th>
                        <th class="text-center px-6 py-3">Pago</th>
                        <th class="text-center px-6 py-3">Pronósticos</th>
                        <th class="text-center px-6 py-3">Registro</th>
                        <th class="text-center px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 font-medium">{{ $user->username }}</td>
                            <td class="px-6 py-3 text-center">
                                @if ($user->role === 'admin')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if ($user->has_paid)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Pagó
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center text-gray-500">{{ $user->total_predictions }}</td>
                            <td class="px-6 py-3 text-center text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-3 text-center">
                                @if ($user->role !== 'admin')
                                    <form method="POST" action="{{ route('admin.users.payment', $user) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="has_paid" value="{{ $user->has_paid ? '0' : '1' }}">
                                        <button
                                            type="submit"
                                            class="text-sm font-medium {{ $user->has_paid ? 'text-red-600 hover:text-red-500' : 'text-green-600 hover:text-green-500' }} cursor-pointer"
                                        >
                                            {{ $user->has_paid ? 'Desmarcar pago' : 'Marcar pago' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
