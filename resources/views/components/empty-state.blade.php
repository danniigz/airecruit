@props(['title', 'description' => null, 'actionHref' => null, 'actionLabel' => null])

<div {{ $attributes->merge(['class' => 'text-center py-10 px-4']) }}>
    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5h6m-6-3h6m-7.5 9h9a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0016.5 4.5h-9A2.25 2.25 0 005.25 6.75v11.5A2.25 2.25 0 007.5 20.25z" />
        </svg>
    </div>

    <h3 class="text-sm font-medium text-slate-900 mb-1">{{ $title }}</h3>

    @if ($description)
        <p class="text-sm text-slate-500 max-w-sm mx-auto mb-4">{{ $description }}</p>
    @endif

    @isset($actionHref)
        <a
            href="{{ $actionHref }}"
            class="inline-flex items-center justify-center rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            {{ $actionLabel }}
        </a>
    @endisset
</div>
