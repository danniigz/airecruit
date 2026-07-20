<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Mis comparaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-lg border border-slate-200">
                <ul class="space-y-3">
                    @forelse ($comparisons as $comparison)
                        <li class="border border-slate-200 rounded-md p-4 flex justify-between items-center gap-4">
                            <div class="min-w-0">
                                <a href="{{ route('comparisons.show', $comparison) }}" class="font-medium text-slate-900 hover:text-brand-700">
                                    {{ $comparison->jobOffer->title }} &middot; {{ $comparison->jobOffer->company }}
                                </a>
                                <p class="text-sm text-slate-500">{{ __('CV:') }} {{ $comparison->cv->original_filename }}</p>
                                <p class="text-xs text-slate-400 mt-1">
                                    {{ __('Comparado el') }} {{ $comparison->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="shrink-0 flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full text-sm font-bold
                                    {{ $comparison->compatibility_score >= 70 ? 'bg-green-50 text-green-700' : ($comparison->compatibility_score >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $comparison->compatibility_score }}
                                </span>
                                <a href="{{ route('comparisons.show', $comparison) }}" class="text-sm font-medium text-brand-700 hover:text-brand-800">
                                    {{ __('Ver detalle') }}
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-slate-500">
                            {{ __('Todavía no has realizado ninguna comparación.') }}
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
