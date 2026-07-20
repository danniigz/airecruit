<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'AIRecruit') }} · Tu copiloto de IA para la búsqueda de empleo</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div class="min-h-screen flex flex-col">
            <header class="border-b border-slate-200 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <x-application-logo />

                        @if (Route::has('login'))
                            <nav class="flex items-center gap-3">
                                @auth
                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="inline-flex items-center justify-center rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        {{ __('Ir al dashboard') }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                                        {{ __('Iniciar sesión') }}
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="inline-flex items-center justify-center rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        >
                                            {{ __('Crear cuenta') }}
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </div>
                </div>
            </header>

            <main class="flex-1">
                <!-- Hero -->
                <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center">
                    <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight text-slate-900">
                        {{ __('Tu copiloto de IA para la búsqueda de empleo') }}
                    </h1>
                    <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-slate-600">
                        {{ __('Analiza tu CV, mide tu compatibilidad con cada oferta y genera cartas de presentación personalizadas, todo impulsado por inteligencia artificial.') }}
                    </p>

                    @if (Route::has('login'))
                        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                            @auth
                                <a
                                    href="{{ route('dashboard') }}"
                                    class="inline-flex items-center justify-center rounded-md bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    {{ __('Ir al dashboard') }}
                                </a>
                            @else
                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="inline-flex items-center justify-center rounded-md bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        {{ __('Crear cuenta gratis') }}
                                    </a>
                                @endif
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center justify-center rounded-md bg-white px-6 py-3 text-sm font-semibold text-slate-700 border border-slate-300 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    {{ __('Iniciar sesión') }}
                                </a>
                            @endauth
                        </div>
                    @endif
                </section>

                <!-- Features -->
                <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 sm:pb-24">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="p-6 bg-white shadow-sm rounded-lg border border-slate-200">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-medium text-slate-900 mb-1">{{ __('Análisis de CV con IA') }}</h3>
                            <p class="text-sm text-slate-500">
                                {{ __('Sube tu CV en PDF y recibe un resumen, tus puntos fuertes, áreas de mejora y skills detectadas automáticamente.') }}
                            </p>
                        </div>

                        <div class="p-6 bg-white shadow-sm rounded-lg border border-slate-200">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.652a3.75 3.75 0 010-5.304m5.304 0a3.75 3.75 0 010 5.304m-7.425 2.121a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.807-3.808-9.98 0-13.788m13.788 0c3.808 3.807 3.808 9.98 0 13.788M12 12h.008v.008H12V12z" />
                                </svg>
                            </div>
                            <h3 class="font-medium text-slate-900 mb-1">{{ __('Scoring de compatibilidad') }}</h3>
                            <p class="text-sm text-slate-500">
                                {{ __('Compara tu CV con cualquier oferta y obtén una puntuación de compatibilidad con fortalezas, carencias y recomendaciones.') }}
                            </p>
                        </div>

                        <div class="p-6 bg-white shadow-sm rounded-lg border border-slate-200">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                                </svg>
                            </div>
                            <h3 class="font-medium text-slate-900 mb-1">{{ __('Cartas de presentación con IA') }}</h3>
                            <p class="text-sm text-slate-500">
                                {{ __('Genera una carta personalizada combinando tu perfil profesional con cada oferta de empleo en segundos.') }}
                            </p>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="border-t border-slate-200 py-6 text-center text-xs text-slate-400">
                {{ __('AIRecruit · TFM del Máster de Desarrollo con IA') }}
            </footer>
        </div>
    </body>
</html>
