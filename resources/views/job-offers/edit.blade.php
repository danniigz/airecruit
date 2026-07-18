<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Editar oferta de empleo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <form method="post" action="{{ route('job-offers.update', $jobOffer) }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    @include('job-offers.partials.form')

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>
                        <a href="{{ route('job-offers.show', $jobOffer) }}" class="text-sm text-slate-600 hover:text-slate-800">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
