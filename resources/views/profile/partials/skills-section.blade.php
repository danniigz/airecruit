@php
    $openForm = old('_form');
@endphp

<section>
    <details class="group" open>
        <summary class="flex items-center justify-between cursor-pointer list-none py-3">
            <h2 class="text-lg font-medium text-slate-900">{{ __('Habilidades') }}</h2>
            <span class="text-slate-400 text-sm group-open:hidden">{{ __('Mostrar') }}</span>
            <span class="text-slate-400 text-sm hidden group-open:inline">{{ __('Ocultar') }}</span>
        </summary>

        <div class="mt-4 space-y-4">
            @if (session('status') === 'skill-created')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Habilidad añadida.') }}</p>
            @elseif (session('status') === 'skill-updated')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Habilidad actualizada.') }}</p>
            @elseif (session('status') === 'skill-deleted')
                <p data-flash-message class="text-sm text-slate-600">{{ __('Habilidad eliminada.') }}</p>
            @endif

            @forelse ($profile->skills as $skill)
                <div class="border border-slate-200 rounded-md p-4 flex justify-between items-center gap-4">
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900">{{ $skill->name }}</p>
                        @if ($skill->level)
                            <p class="text-sm text-slate-500">{{ $skill->level }}</p>
                        @endif
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <x-secondary-button type="button" data-modal-open="skill-edit-{{ $skill->id }}">
                            {{ __('Editar') }}
                        </x-secondary-button>

                        <form method="post" action="{{ route('profile.skills.destroy', $skill) }}" onsubmit="return confirm('{{ __('¿Eliminar esta habilidad?') }}');">
                            @csrf
                            @method('delete')
                            <x-danger-button>{{ __('Eliminar') }}</x-danger-button>
                        </form>
                    </div>
                </div>

                @php $isEditingThis = $openForm === 'skill-edit-'.$skill->id; @endphp
                <x-modal name="skill-edit-{{ $skill->id }}" :show="$isEditingThis">
                    <form method="post" action="{{ route('profile.skills.update', $skill) }}" class="p-6 space-y-4">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="_form" value="skill-edit-{{ $skill->id }}">

                        <h3 class="text-lg font-medium text-slate-900">{{ __('Editar habilidad') }}</h3>

                        <div>
                            <x-input-label for="name-skill-edit-{{ $skill->id }}" :value="__('Nombre')" />
                            <x-text-input id="name-skill-edit-{{ $skill->id }}" name="name" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('name') : $skill->name" required />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('name') : []" />
                        </div>

                        <div>
                            <x-input-label for="level-skill-edit-{{ $skill->id }}" :value="__('Nivel')" />
                            <x-text-input id="level-skill-edit-{{ $skill->id }}" name="level" type="text" class="mt-1 block w-full" placeholder="{{ __('Básico, Intermedio, Avanzado, Experto…') }}" :value="$isEditingThis ? old('level') : $skill->level" />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('level') : []" />
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <x-secondary-button type="button" data-modal-close>{{ __('Cancelar') }}</x-secondary-button>
                            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                        </div>
                    </form>
                </x-modal>
            @empty
                <p class="text-sm text-slate-500">{{ __('Todavía no has añadido habilidades.') }}</p>
            @endforelse

            <x-secondary-button type="button" data-modal-open="skill-create">
                {{ __('Añadir habilidad') }}
            </x-secondary-button>

            @php $isCreating = $openForm === 'skill-create'; @endphp
            <x-modal name="skill-create" :show="$isCreating">
                <form method="post" action="{{ route('profile.skills.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="skill-create">

                    <h3 class="text-lg font-medium text-slate-900">{{ __('Añadir habilidad') }}</h3>

                    <div>
                        <x-input-label for="name-skill-create" :value="__('Nombre')" />
                        <x-text-input id="name-skill-create" name="name" type="text" class="mt-1 block w-full" :value="$isCreating ? old('name') : ''" required />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('name') : []" />
                    </div>

                    <div>
                        <x-input-label for="level-skill-create" :value="__('Nivel')" />
                        <x-text-input id="level-skill-create" name="level" type="text" class="mt-1 block w-full" placeholder="{{ __('Básico, Intermedio, Avanzado, Experto…') }}" :value="$isCreating ? old('level') : ''" />
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
