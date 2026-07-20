<x-app-layout :title="__('Comparación:').' '.$comparison->jobOffer->title">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Resultado de la comparación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <div class="flex justify-between items-start gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900">{{ $comparison->jobOffer->title }}</h3>
                        <p class="text-slate-600">{{ $comparison->jobOffer->company }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ __('CV comparado:') }} {{ $comparison->cv->original_filename }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ __('Comparado el') }} {{ $comparison->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <a href="{{ route('comparisons.index') }}" class="shrink-0 text-sm font-medium text-brand-700 hover:text-brand-800">
                        {{ __('Volver a mis comparaciones') }}
                    </a>
                </div>

                <div class="flex justify-center mb-8">
                    @include('comparisons.partials.score-circle', ['score' => $comparison->compatibility_score])
                </div>

                @include('comparisons.partials.breakdown', ['feedback' => $comparison->ai_feedback])
            </div>
        </div>
    </div>
</x-app-layout>
