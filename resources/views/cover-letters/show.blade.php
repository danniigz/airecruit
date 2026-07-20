<x-app-layout :title="__('Carta:').' '.$coverLetter->jobOffer->title">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Carta de presentación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <div class="flex justify-between items-start gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-medium text-slate-900">{{ $coverLetter->jobOffer->title }}</h3>
                        <p class="text-slate-600">{{ $coverLetter->jobOffer->company }}</p>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ __('Generada el') }} {{ $coverLetter->generated_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <a href="{{ route('cover-letters.index') }}" class="shrink-0 text-sm font-medium text-brand-700 hover:text-brand-800">
                        {{ __('Volver a mis cartas') }}
                    </a>
                </div>

                <div class="flex gap-3 mb-4">
                    <x-secondary-button type="button" data-copy-target="#cover-letter-content">
                        {{ __('Copiar al portapapeles') }}
                    </x-secondary-button>
                    <x-secondary-button
                        type="button"
                        data-download-target="#cover-letter-content"
                        data-download-filename="carta-{{ Str::slug($coverLetter->jobOffer->company.'-'.$coverLetter->jobOffer->title) }}.txt"
                    >
                        {{ __('Descargar .txt') }}
                    </x-secondary-button>
                </div>

                <div id="cover-letter-content" class="whitespace-pre-line text-slate-700 border-t border-slate-200 pt-4">{{ $coverLetter->content }}</div>
            </div>
        </div>
    </div>

    @vite('resources/js/cover-letter.js')
</x-app-layout>
