<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased">
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    {{-- Logo / Brand --}}
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600 hover:text-indigo-500">
                            ⚽ {{ config('app.name') }}
                        </a>
                    </div>

                    {{-- Nav links --}}
                    <div class="flex items-center gap-1 sm:gap-2">
                        @auth
                            <a href="{{ route('groups') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('groups') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                Partidos
                            </a>
                            <a href="{{ route('predictions') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('predictions') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                Mis Pronósticos
                            </a>
                            <a href="{{ route('leaderboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('leaderboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                Tabla
                            </a>
                            <a href="{{ route('results') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('results') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                Resultados
                            </a>

                            @if (auth()->user()->isAdmin())
                                <div class="hidden sm:block w-px h-6 bg-gray-300 mx-1"></div>
                                <a href="{{ route('admin.settings') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.*') ? 'bg-amber-50 text-amber-700' : 'text-gray-700 hover:bg-gray-100' }}">
                                    Admin
                                </a>
                            @endif

                            <div class="hidden sm:block w-px h-6 bg-gray-300 mx-1"></div>

                            <span class="text-sm text-gray-500 hidden sm:inline">
                                {{ auth()->user()->username }}
                            </span>

                            <a href="{{ route('password.form') }}" class="px-2 py-2 rounded-md text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100" title="Cambiar contraseña">
                                🔑
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-red-600">
                                    Salir
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500">
                                Ingresar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Session messages --}}
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </body>
</html>
