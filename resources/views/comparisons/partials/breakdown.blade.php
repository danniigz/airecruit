@php
    $fortalezas = (array) data_get($feedback, 'fortalezas', []);
    $carencias = (array) data_get($feedback, 'carencias', []);
    $recomendaciones = (array) data_get($feedback, 'recomendaciones', []);
@endphp

<div class="grid sm:grid-cols-2 gap-6 mb-6">
    <div>
        <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Fortalezas que encajan') }}</h4>
        @if (count($fortalezas))
            <ul class="list-disc list-inside text-slate-700 space-y-1">
                @foreach ($fortalezas as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-slate-400 text-sm">{{ __('Sin datos.') }}</p>
        @endif
    </div>

    <div>
        <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Carencias detectadas') }}</h4>
        @if (count($carencias))
            <ul class="list-disc list-inside text-slate-700 space-y-1">
                @foreach ($carencias as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-slate-400 text-sm">{{ __('Sin datos.') }}</p>
        @endif
    </div>
</div>

<div>
    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Recomendaciones') }}</h4>
    @if (count($recomendaciones))
        <ul class="list-disc list-inside text-slate-700 space-y-1">
            @foreach ($recomendaciones as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @else
        <p class="text-slate-400 text-sm">{{ __('Sin datos.') }}</p>
    @endif
</div>
