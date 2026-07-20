<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <p class="text-slate-600">
                {{ __('Hola, :name. Este es el resumen de tu actividad.', ['name' => Auth::user()->name]) }}
            </p>

            @if ($cvCount === 0)
                <div class="p-4 sm:p-8 bg-brand-50 border border-brand-200 rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900 mb-1">{{ __('Todavía no has subido ningún CV') }}</h3>
                        <p class="text-sm text-slate-600">
                            {{ __('Sube tu CV en PDF y deja que la IA lo analice para empezar a comparar ofertas y generar cartas de presentación.') }}
                        </p>
                    </div>
                    <a
                        href="{{ route('cvs.index') }}"
                        class="shrink-0 inline-flex items-center justify-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition"
                    >
                        {{ __('Subir mi primer CV') }}
                    </a>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="p-6 bg-white shadow-sm rounded-lg border border-slate-200">
                    <p class="text-sm font-medium text-slate-500">{{ __('CVs subidos') }}</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $cvCount }}</p>
                </div>

                <div class="p-6 bg-white shadow-sm rounded-lg border border-slate-200">
                    <p class="text-sm font-medium text-slate-500">{{ __('Comparaciones realizadas') }}</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $comparisonCount }}</p>
                </div>

                <div class="p-6 bg-white shadow-sm rounded-lg border border-slate-200">
                    <p class="text-sm font-medium text-slate-500">{{ __('Cartas generadas') }}</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $coverLetterCount }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-slate-900 mb-4">{{ __('Accesos directos') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="{{ route('profile.index') }}" class="p-6 bg-white shadow-sm rounded-lg border border-slate-200 hover:border-brand-300 hover:shadow transition">
                        <p class="font-medium text-slate-900">{{ __('Mi perfil') }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Gestiona tu experiencia, formación, skills e idiomas.') }}</p>
                    </a>

                    <a href="{{ route('cvs.index') }}" class="p-6 bg-white shadow-sm rounded-lg border border-slate-200 hover:border-brand-300 hover:shadow transition">
                        <p class="font-medium text-slate-900">{{ __('CVs') }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Sube y consulta el análisis IA de tus CVs.') }}</p>
                    </a>

                    <a href="{{ route('job-offers.index') }}" class="p-6 bg-white shadow-sm rounded-lg border border-slate-200 hover:border-brand-300 hover:shadow transition">
                        <p class="font-medium text-slate-900">{{ __('Ofertas') }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Añade ofertas de empleo para comparar y generar cartas.') }}</p>
                    </a>

                    <a href="{{ route('comparisons.index') }}" class="p-6 bg-white shadow-sm rounded-lg border border-slate-200 hover:border-brand-300 hover:shadow transition">
                        <p class="font-medium text-slate-900">{{ __('Comparaciones') }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Consulta la puntuación de compatibilidad con cada oferta.') }}</p>
                    </a>

                    <a href="{{ route('cover-letters.index') }}" class="p-6 bg-white shadow-sm rounded-lg border border-slate-200 hover:border-brand-300 hover:shadow transition">
                        <p class="font-medium text-slate-900">{{ __('Cartas') }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Revisa tus cartas de presentación generadas por IA.') }}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
