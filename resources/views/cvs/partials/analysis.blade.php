@php
    $puntosFuertes = (array) data_get($analysis, 'puntos_fuertes', []);
    $areasMejora = (array) data_get($analysis, 'areas_mejora', []);
    $skills = (array) data_get($analysis, 'skills_principales', []);
    $anosExperiencia = data_get($analysis, 'anos_experiencia_aproximados');
@endphp

<div data-cv-analysis>
    <p class="text-xs text-slate-400 mb-4">{{ __('Analizado el') }} {{ $analyzedAt }}</p>

    <div class="mb-6">
        <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Resumen') }}</h4>
        <p class="text-slate-700">{{ data_get($analysis, 'resumen', '—') }}</p>
    </div>

    <div class="grid sm:grid-cols-2 gap-6 mb-6">
        <div>
            <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Puntos fuertes') }}</h4>
            @if (count($puntosFuertes))
                <ul class="list-disc list-inside text-slate-700 space-y-1">
                    @foreach ($puntosFuertes as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-slate-400 text-sm">{{ __('Sin datos.') }}</p>
            @endif
        </div>

        <div>
            <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Áreas de mejora') }}</h4>
            @if (count($areasMejora))
                <ul class="list-disc list-inside text-slate-700 space-y-1">
                    @foreach ($areasMejora as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-slate-400 text-sm">{{ __('Sin datos.') }}</p>
            @endif
        </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-6">
        <div>
            <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Años de experiencia (aprox.)') }}</h4>
            <p class="text-slate-700">{{ $anosExperiencia ?? '—' }}</p>
        </div>

        <div>
            <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">{{ __('Skills principales') }}</h4>
            @if (count($skills))
                <div class="flex flex-wrap gap-2">
                    @foreach ($skills as $skill)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700">{{ $skill }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-slate-400 text-sm">{{ __('Sin datos.') }}</p>
            @endif
        </div>
    </div>
</div>
