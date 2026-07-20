@props(['type' => 'success'])

@php
$styles = match ($type) {
    'error' => 'bg-red-50 border-red-200 text-red-800',
    default => 'bg-green-50 border-green-200 text-green-800',
};

$iconPath = match ($type) {
    'error' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
    default => 'M9 12.75l2.25 2.25 4.5-4.5m5.25 2.25a9 9 0 11-18 0 9 9 0 0118 0z',
};
@endphp

<div data-flash-message {{ $attributes->merge(['class' => "flex items-center gap-2 rounded-md border px-4 py-3 text-sm font-medium {$styles}"]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5 shrink-0">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
    </svg>
    <span>{{ $slot }}</span>
</div>
