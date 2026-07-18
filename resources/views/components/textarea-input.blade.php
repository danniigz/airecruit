@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 focus:border-brand-500 focus:ring-brand-500 rounded-md shadow-sm text-sm']) }}>{{ $slot }}</textarea>
