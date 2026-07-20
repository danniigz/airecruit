<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Detalle de la oferta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'job-offer-created')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Oferta añadida.') }}</p>
            @elseif (session('status') === 'job-offer-updated')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Oferta actualizada.') }}</p>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <div class="flex justify-between items-start gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900">{{ $jobOffer->title }}</h3>
                        <p class="text-slate-600">{{ $jobOffer->company }}</p>
                        @if ($jobOffer->url)
                            <a href="{{ $jobOffer->url }}" target="_blank" rel="noopener noreferrer" class="text-sm text-brand-700 hover:text-brand-800 break-all">
                                {{ $jobOffer->url }}
                            </a>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">
                            {{ __('Añadida el') }} {{ $jobOffer->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <a href="{{ route('job-offers.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 self-center">
                            {{ __('Volver') }}
                        </a>
                        <a href="{{ route('job-offers.edit', $jobOffer) }}" class="text-sm font-medium text-brand-700 hover:text-brand-800 self-center">
                            {{ __('Editar') }}
                        </a>
                        <form method="post" action="{{ route('job-offers.destroy', $jobOffer) }}" onsubmit="return confirm('{{ __('¿Eliminar esta oferta?') }}');">
                            @csrf
                            @method('delete')
                            <x-danger-button>{{ __('Eliminar') }}</x-danger-button>
                        </form>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Descripción') }}</h4>
                    <p class="text-slate-700 whitespace-pre-line">{{ $jobOffer->description }}</p>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-1">{{ __('Comparar compatibilidad') }}</h3>
                <p class="text-sm text-slate-500 mb-4">
                    {{ __('Selecciona un CV ya analizado para calcular la compatibilidad con esta oferta mediante IA.') }}
                </p>

                @if ($analyzedCvs->isEmpty())
                    <p class="text-sm text-amber-700">
                        {{ __('Todavía no tienes ningún CV analizado.') }}
                        <a href="{{ route('cvs.index') }}" class="font-medium text-brand-700 hover:text-brand-800">{{ __('Sube y analiza un CV') }}</a>
                        {{ __('para poder comparar.') }}
                    </p>
                @else
                    <form id="comparison-launcher-form" data-job-offer-id="{{ $jobOffer->id }}" class="flex flex-col sm:flex-row gap-3 sm:items-center">
                        @csrf
                        <select name="cv_id" id="comparison-variable-select" class="block w-full sm:w-72 rounded-md border-slate-300 shadow-sm text-sm focus:border-brand-500 focus:ring-brand-500">
                            @foreach ($analyzedCvs as $option)
                                <option value="{{ $option->id }}">{{ $option->original_filename }}</option>
                            @endforeach
                        </select>
                        <x-primary-button type="submit" id="comparison-launcher-submit">
                            {{ __('Comparar compatibilidad') }}
                        </x-primary-button>
                    </form>
                @endif

                <div id="comparison-launcher-status" class="hidden mt-4 text-sm"></div>
                <div id="comparison-launcher-result" class="hidden mt-6 border-t border-slate-200 pt-6"></div>
            </div>
        </div>
    </div>

    @vite('resources/js/comparison.js')
</x-app-layout>
