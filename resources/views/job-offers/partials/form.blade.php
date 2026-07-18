@php
    $jobOffer ??= null;
@endphp

<div>
    <x-input-label for="title" :value="__('Puesto')" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $jobOffer?->title)" maxlength="255" required autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div>
    <x-input-label for="company" :value="__('Empresa')" />
    <x-text-input id="company" name="company" type="text" class="mt-1 block w-full" :value="old('company', $jobOffer?->company)" maxlength="255" required />
    <x-input-error class="mt-2" :messages="$errors->get('company')" />
</div>

<div>
    <x-input-label for="url" :value="__('URL de la oferta (opcional)')" />
    <x-text-input id="url" name="url" type="url" class="mt-1 block w-full" :value="old('url', $jobOffer?->url)" placeholder="https://..." maxlength="2048" />
    <x-input-error class="mt-2" :messages="$errors->get('url')" />
</div>

<div>
    <x-input-label for="description" :value="__('Descripción de la oferta')" />
    <p class="text-sm text-slate-500 mb-1">{{ __('Pega aquí el texto completo de la oferta.') }}</p>
    <x-textarea-input id="description" name="description" class="mt-1 block w-full" rows="14" maxlength="20000" required>{{ old('description', $jobOffer?->description) }}</x-textarea-input>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>
