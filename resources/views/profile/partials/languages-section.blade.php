@php
    $openForm = old('_form');
@endphp

<section>
    <details class="group" open>
        <summary class="flex items-center justify-between cursor-pointer list-none py-3">
            <h2 class="text-lg font-medium text-slate-900">{{ __('Idiomas') }}</h2>
            <span class="text-slate-400 text-sm group-open:hidden">{{ __('Mostrar') }}</span>
            <span class="text-slate-400 text-sm hidden group-open:inline">{{ __('Ocultar') }}</span>
        </summary>

        <div class="mt-4 space-y-4">
            @if (session('status') === 'language-created')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Idioma añadido.') }}</p>
            @elseif (session('status') === 'language-updated')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Idioma actualizado.') }}</p>
            @elseif (session('status') === 'language-deleted')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Idioma eliminado.') }}</p>
            @endif

            @forelse ($profile->languages as $language)
                <div class="border border-slate-200 rounded-md p-4 flex justify-between items-center gap-4">
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900">{{ $language->name }}</p>
                        @if ($language->level)
                            <p class="text-sm text-slate-500">{{ $language->level }}</p>
                        @endif
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <x-secondary-button type="button" data-modal-open="language-edit-{{ $language->id }}">
                            {{ __('Editar') }}
                        </x-secondary-button>

                        <form method="post" action="{{ route('profile.languages.destroy', $language) }}" onsubmit="return confirm('{{ __('¿Eliminar este idioma?') }}');">
                            @csrf
                            @method('delete')
                            <x-danger-button>{{ __('Eliminar') }}</x-danger-button>
                        </form>
                    </div>
                </div>

                @php $isEditingThis = $openForm === 'language-edit-'.$language->id; @endphp
                <x-modal name="language-edit-{{ $language->id }}" :show="$isEditingThis">
                    <form method="post" action="{{ route('profile.languages.update', $language) }}" class="p-6 space-y-4">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="_form" value="language-edit-{{ $language->id }}">

                        <h3 class="text-lg font-medium text-slate-900">{{ __('Editar idioma') }}</h3>

                        <div>
                            <x-input-label for="name-language-edit-{{ $language->id }}" :value="__('Nombre')" />
                            <x-text-input id="name-language-edit-{{ $language->id }}" name="name" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('name') : $language->name" required />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('name') : []" />
                        </div>

                        <div>
                            <x-input-label for="level-language-edit-{{ $language->id }}" :value="__('Nivel')" />
                            <x-text-input id="level-language-edit-{{ $language->id }}" name="level" type="text" class="mt-1 block w-full" placeholder="{{ __('A1, B2, C1, Nativo…') }}" :value="$isEditingThis ? old('level') : $language->level" />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('level') : []" />
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <x-secondary-button type="button" data-modal-close>{{ __('Cancelar') }}</x-secondary-button>
                            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                        </div>
                    </form>
                </x-modal>
            @empty
                <p class="text-sm text-slate-500">{{ __('Todavía no has añadido idiomas.') }}</p>
            @endforelse

            <x-secondary-button type="button" data-modal-open="language-create">
                {{ __('Añadir idioma') }}
            </x-secondary-button>

            @php $isCreating = $openForm === 'language-create'; @endphp
            <x-modal name="language-create" :show="$isCreating">
                <form method="post" action="{{ route('profile.languages.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="language-create">

                    <h3 class="text-lg font-medium text-slate-900">{{ __('Añadir idioma') }}</h3>

                    <div>
                        <x-input-label for="name-language-create" :value="__('Nombre')" />
                        <x-text-input id="name-language-create" name="name" type="text" class="mt-1 block w-full" :value="$isCreating ? old('name') : ''" required />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('name') : []" />
                    </div>

                    <div>
                        <x-input-label for="level-language-create" :value="__('Nivel')" />
                        <x-text-input id="level-language-create" name="level" type="text" class="mt-1 block w-full" placeholder="{{ __('A1, B2, C1, Nativo…') }}" :value="$isCreating ? old('level') : ''" />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('level') : []" />
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <x-secondary-button type="button" data-modal-close>{{ __('Cancelar') }}</x-secondary-button>
                        <x-primary-button>{{ __('Añadir') }}</x-primary-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </details>
</section>
