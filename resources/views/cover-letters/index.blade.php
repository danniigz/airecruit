<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Mis cartas de presentación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <ul class="space-y-3">
                    @forelse ($coverLetters as $coverLetter)
                        <li class="border border-slate-200 rounded-md p-4 flex justify-between items-center gap-4">
                            <div class="min-w-0">
                                <a href="{{ route('cover-letters.show', $coverLetter) }}" class="font-medium text-slate-900 hover:text-brand-700">
                                    {{ $coverLetter->jobOffer->title }} &middot; {{ $coverLetter->jobOffer->company }}
                                </a>
                                <p class="text-xs text-slate-400 mt-1">
                                    {{ __('Generada el') }} {{ $coverLetter->generated_at?->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <a href="{{ route('cover-letters.show', $coverLetter) }}" class="shrink-0 text-sm font-medium text-brand-700 hover:text-brand-800">
                                {{ __('Ver carta') }}
                            </a>
                        </li>
                    @empty
                        <li class="text-sm text-slate-500">
                            {{ __('Todavía no has generado ninguna carta de presentación.') }}
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
