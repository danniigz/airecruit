<x-app-layout title="Ofertas de empleo">
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Ofertas de empleo') }}
            </h2>

            <a href="{{ route('job-offers.create') }}" class="inline-flex items-center justify-center rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Nueva oferta') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'job-offer-deleted')
                <x-flash-message>{{ __('Oferta eliminada.') }}</x-flash-message>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <ul class="space-y-3">
                    @forelse ($jobOffers as $jobOffer)
                        <li class="border border-slate-200 rounded-md p-4 flex justify-between items-start gap-4">
                            <div class="min-w-0">
                                <a href="{{ route('job-offers.show', $jobOffer) }}" class="font-medium text-slate-900 hover:text-brand-700">
                                    {{ $jobOffer->title }}
                                </a>
                                <p class="text-sm text-slate-500">{{ $jobOffer->company }}</p>
                                <p class="text-xs text-slate-400 mt-1">
                                    {{ __('Añadida el') }} {{ $jobOffer->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="flex gap-2 shrink-0">
                                <a href="{{ route('job-offers.show', $jobOffer) }}" class="text-sm font-medium text-brand-700 hover:text-brand-800 self-center">
                                    {{ __('Ver') }}
                                </a>
                                <a href="{{ route('job-offers.edit', $jobOffer) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 self-center">
                                    {{ __('Editar') }}
                                </a>
                                <form method="post" action="{{ route('job-offers.destroy', $jobOffer) }}" onsubmit="return confirm('{{ __('¿Eliminar esta oferta?') }}');">
                                    @csrf
                                    @method('delete')
                                    <x-danger-button>{{ __('Eliminar') }}</x-danger-button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li>
                            <x-empty-state
                                :title="__('Todavía no has añadido ninguna oferta de empleo')"
                                :description="__('Añade la primera oferta para poder comparar tu compatibilidad y generar una carta de presentación con IA.')"
                                :action-href="route('job-offers.create')"
                                :action-label="__('Añadir oferta')"
                            />
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
