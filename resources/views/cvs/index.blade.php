<x-app-layout title="Mis CVs">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Mis CVs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-1">{{ __('Subir CV') }}</h3>
                <p class="text-sm text-slate-500 mb-4">
                    {{ __('Sube tu CV en PDF (máx. 2MB) y la IA lo analizará automáticamente.') }}
                </p>

                <form id="cv-upload-form" class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    @csrf
                    <input
                        type="file"
                        id="cv-file-input"
                        name="cv"
                        accept="application/pdf"
                        required
                        class="block w-full text-sm text-slate-600 border border-slate-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100"
                    >
                    <x-primary-button type="submit" id="cv-upload-submit">
                        {{ __('Subir y analizar') }}
                    </x-primary-button>
                </form>

                <div id="cv-upload-status" class="hidden mt-4 text-sm"></div>

                <div id="cv-upload-result" class="hidden mt-6 border-t border-slate-200 pt-6"></div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-4">{{ __('CVs subidos') }}</h3>

                <ul id="cv-list" class="space-y-3">
                    @forelse ($cvs as $cv)
                        <li data-cv-item data-cv-id="{{ $cv->id }}" class="border border-slate-200 rounded-md p-4 flex justify-between items-center gap-4">
                            <div class="min-w-0">
                                <p class="font-medium text-slate-900 truncate">{{ $cv->original_filename }}</p>
                                <p class="text-sm text-slate-500">
                                    {{ __('Subido el') }} {{ $cv->created_at->format('d/m/Y H:i') }}
                                    &middot;
                                    @if ($cv->analyzed_at)
                                        <span class="text-green-700">{{ __('Analizado') }}</span>
                                    @else
                                        <span class="text-amber-700">{{ __('Sin analizar') }}</span>
                                    @endif
                                </p>
                            </div>

                            <a href="{{ route('cvs.show', $cv) }}" class="shrink-0 text-sm font-medium text-brand-700 hover:text-brand-800">
                                {{ __('Ver detalle') }}
                            </a>
                        </li>
                    @empty
                        <li id="cv-list-empty" class="border border-dashed border-slate-300 rounded-md p-6 text-center text-sm text-slate-500">
                            {{ __('Todavía no has subido ningún CV. Usa el formulario de arriba para subir el primero.') }}
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    @vite('resources/js/cv-upload.js')
</x-app-layout>
