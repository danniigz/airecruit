@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl',
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    data-modal="{{ $name }}"
    class="{{ $show ? '' : 'hidden' }} fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
>
    <div data-modal-backdrop class="fixed inset-0 bg-slate-500/75 transition-opacity"></div>

    <div class="relative mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto">
        {{ $slot }}
    </div>
</div>
