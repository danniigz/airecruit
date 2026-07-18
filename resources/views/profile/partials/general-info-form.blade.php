<section>
    <header>
        <h2 class="text-lg font-medium text-slate-900">
            {{ __('Datos generales') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __('Esta información aparecerá como cabecera de tu perfil profesional.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="headline" :value="__('Titular profesional')" />
            <x-text-input id="headline" name="headline" type="text" class="mt-1 block w-full" :value="old('headline', $profile->headline)" maxlength="255" />
            <x-input-error class="mt-2" :messages="$errors->get('headline')" />
        </div>

        <div>
            <x-input-label for="summary" :value="__('Resumen')" />
            <x-textarea-input id="summary" name="summary" class="mt-1 block w-full" rows="4" maxlength="2000">{{ old('summary', $profile->summary) }}</x-textarea-input>
            <x-input-error class="mt-2" :messages="$errors->get('summary')" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <x-input-label for="phone" :value="__('Teléfono')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $profile->phone)" maxlength="30" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="location" :value="__('Ubicación')" />
                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $profile->location)" maxlength="255" />
                <x-input-error class="mt-2" :messages="$errors->get('location')" />
            </div>

            <div>
                <x-input-label for="linkedin_url" :value="__('LinkedIn')" />
                <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full" :value="old('linkedin_url', $profile->linkedin_url)" placeholder="https://linkedin.com/in/..." maxlength="255" />
                <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
            </div>

            <div>
                <x-input-label for="portfolio_url" :value="__('Portfolio')" />
                <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="mt-1 block w-full" :value="old('portfolio_url', $profile->portfolio_url)" placeholder="https://..." maxlength="255" />
                <x-input-error class="mt-2" :messages="$errors->get('portfolio_url')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
