<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Análisis del CV') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <div class="flex justify-between items-start gap-4 mb-2">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900">{{ $cv->original_filename }}</h3>
                        <p class="text-sm text-slate-500">
                            {{ __('Subido el') }} {{ $cv->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <a href="{{ route('cvs.index') }}" class="shrink-0 text-sm font-medium text-brand-700 hover:text-brand-800">
                        {{ __('Volver a mis CVs') }}
                    </a>
                </div>

                <div id="cv-analyze-status" class="hidden mt-4 text-sm"></div>

                <div id="cv-analysis-container" class="mt-6" data-cv-id="{{ $cv->id }}">
                    @if ($cv->ai_analysis)
                        @include('cvs.partials.analysis', ['analysis' => $cv->ai_analysis, 'analyzedAt' => $cv->analyzed_at->format('d/m/Y H:i')])
                    @else
                        <div id="cv-not-analyzed" class="text-center py-8">
                            <p class="text-slate-500 mb-4">{{ __('Este CV todavía no se ha analizado.') }}</p>
                            <x-primary-button type="button" id="cv-analyze-retry-btn">
                                {{ __('Analizar con IA') }}
                            </x-primary-button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/cv-upload.js')
</x-app-layout>
