@props(['iconOnly' => false])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-brand-600 to-brand-500 text-sm font-bold text-white shadow-sm">
        AI
    </span>

    @unless ($iconOnly)
        <span class="text-lg font-semibold tracking-tight text-slate-900">AIRecruit</span>
    @endunless
</span>
