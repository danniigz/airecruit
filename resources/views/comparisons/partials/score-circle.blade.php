@php
    $radius = 60;
    $circumference = 2 * M_PI * $radius;
    $clamped = max(0, min(100, $score));
    $offset = $circumference - ($circumference * $clamped / 100);
    $ringColor = $clamped >= 70 ? '#15803d' : ($clamped >= 40 ? '#b45309' : '#b91c1c');
    $textColorClass = $clamped >= 70 ? 'text-green-700' : ($clamped >= 40 ? 'text-amber-700' : 'text-red-700');
@endphp

<div class="relative w-36 h-36">
    <svg width="144" height="144" viewBox="0 0 144 144" class="absolute inset-0">
        <circle cx="72" cy="72" r="{{ $radius }}" fill="none" stroke="#e2e8f0" stroke-width="12" />
        <circle cx="72" cy="72" r="{{ $radius }}" fill="none" stroke="{{ $ringColor }}" stroke-width="12" stroke-linecap="round"
            stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}"
            transform="rotate(-90 72 72)" />
    </svg>
    <div class="absolute inset-0 flex flex-col items-center justify-center">
        <span class="text-3xl font-bold {{ $textColorClass }}">{{ $clamped }}</span>
        <span class="text-xs text-slate-500">/ 100</span>
    </div>
</div>
