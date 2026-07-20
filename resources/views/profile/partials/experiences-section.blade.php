@php
    $openForm = old('_form');
@endphp

<section>
    <details class="group" open>
        <summary class="flex items-center justify-between cursor-pointer list-none py-3">
            <h2 class="text-lg font-medium text-slate-900">{{ __('Experiencia laboral') }}</h2>
            <span class="text-slate-400 text-sm group-open:hidden">{{ __('Mostrar') }}</span>
            <span class="text-slate-400 text-sm hidden group-open:inline">{{ __('Ocultar') }}</span>
        </summary>

        <div class="mt-4 space-y-4">
            @if (session('status') === 'experience-created')
                <x-flash-message>{{ __('Experiencia añadida.') }}</x-flash-message>
            @elseif (session('status') === 'experience-updated')
                <x-flash-message>{{ __('Experiencia actualizada.') }}</x-flash-message>
            @elseif (session('status') === 'experience-deleted')
                <x-flash-message>{{ __('Experiencia eliminada.') }}</x-flash-message>
            @endif

            @forelse ($profile->experiences as $experience)
                <div class="border border-slate-200 rounded-md p-4 flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900">{{ $experience->position }} · {{ $experience->company }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $experience->start_date->format('m/Y') }}
                            &mdash;
                            {{ $experience->is_current ? __('Actualidad') : $experience->end_date?->format('m/Y') }}
                        </p>
                        @if ($experience->description)
                            <p class="text-sm text-slate-600 mt-1 whitespace-pre-line">{{ $experience->description }}</p>
                        @endif
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <x-secondary-button type="button" data-modal-open="experience-edit-{{ $experience->id }}">
                            {{ __('Editar') }}
                        </x-secondary-button>

                        <form method="post" action="{{ route('profile.experiences.destroy', $experience) }}" onsubmit="return confirm('{{ __('¿Eliminar esta experiencia?') }}');">
                            @csrf
                            @method('delete')
                            <x-danger-button>{{ __('Eliminar') }}</x-danger-button>
                        </form>
                    </div>
                </div>

                @php $isEditingThis = $openForm === 'experience-edit-'.$experience->id; @endphp
                <x-modal name="experience-edit-{{ $experience->id }}" :show="$isEditingThis">
                    <form method="post" action="{{ route('profile.experiences.update', $experience) }}" class="p-6 space-y-4">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="_form" value="experience-edit-{{ $experience->id }}">

                        <h3 class="text-lg font-medium text-slate-900">{{ __('Editar experiencia') }}</h3>

                        <div>
                            <x-input-label for="company-edit-{{ $experience->id }}" :value="__('Empresa')" />
                            <x-text-input id="company-edit-{{ $experience->id }}" name="company" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('company') : $experience->company" required />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('company') : []" />
                        </div>

                        <div>
                            <x-input-label for="position-edit-{{ $experience->id }}" :value="__('Puesto')" />
                            <x-text-input id="position-edit-{{ $experience->id }}" name="position" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('position') : $experience->position" required />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('position') : []" />
                        </div>

                        <div>
                            <x-input-label for="description-edit-{{ $experience->id }}" :value="__('Descripción')" />
                            <x-textarea-input id="description-edit-{{ $experience->id }}" name="description" class="mt-1 block w-full" rows="3">{{ $isEditingThis ? old('description') : $experience->description }}</x-textarea-input>
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('description') : []" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_date-edit-{{ $experience->id }}" :value="__('Fecha de inicio')" />
                                <x-text-input id="start_date-edit-{{ $experience->id }}" name="start_date" type="date" class="mt-1 block w-full" :value="$isEditingThis ? old('start_date') : $experience->start_date->format('Y-m-d')" required />
                                <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('start_date') : []" />
                            </div>

                            <div>
                                <x-input-label for="end_date-edit-{{ $experience->id }}" :value="__('Fecha de fin')" />
                                <x-text-input id="end_date-edit-{{ $experience->id }}" name="end_date" type="date" class="mt-1 block w-full" :value="$isEditingThis ? old('end_date') : $experience->end_date?->format('Y-m-d')" />
                                <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('end_date') : []" />
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="is_current-edit-{{ $experience->id }}" name="is_current" type="checkbox" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" @checked($isEditingThis ? old('is_current') : $experience->is_current)>
                            <x-input-label for="is_current-edit-{{ $experience->id }}" :value="__('Es mi puesto actual')" />
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <x-secondary-button type="button" data-modal-close>{{ __('Cancelar') }}</x-secondary-button>
                            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                        </div>
                    </form>
                </x-modal>
            @empty
                <div class="border border-dashed border-slate-300 rounded-md p-4 text-center text-sm text-slate-500">
                    {{ __('Todavía no has añadido experiencia laboral.') }}
                </div>
            @endforelse

            <x-secondary-button type="button" data-modal-open="experience-create">
                {{ __('Añadir experiencia') }}
            </x-secondary-button>

            @php $isCreating = $openForm === 'experience-create'; @endphp
            <x-modal name="experience-create" :show="$isCreating">
                <form method="post" action="{{ route('profile.experiences.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="experience-create">

                    <h3 class="text-lg font-medium text-slate-900">{{ __('Añadir experiencia') }}</h3>

                    <div>
                        <x-input-label for="company-create" :value="__('Empresa')" />
                        <x-text-input id="company-create" name="company" type="text" class="mt-1 block w-full" :value="$isCreating ? old('company') : ''" required />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('company') : []" />
                    </div>

                    <div>
                        <x-input-label for="position-create" :value="__('Puesto')" />
                        <x-text-input id="position-create" name="position" type="text" class="mt-1 block w-full" :value="$isCreating ? old('position') : ''" required />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('position') : []" />
                    </div>

                    <div>
                        <x-input-label for="description-create" :value="__('Descripción')" />
                        <x-textarea-input id="description-create" name="description" class="mt-1 block w-full" rows="3">{{ $isCreating ? old('description') : '' }}</x-textarea-input>
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('description') : []" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="start_date-create" :value="__('Fecha de inicio')" />
                            <x-text-input id="start_date-create" name="start_date" type="date" class="mt-1 block w-full" :value="$isCreating ? old('start_date') : ''" required />
                            <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('start_date') : []" />
                        </div>

                        <div>
                            <x-input-label for="end_date-create" :value="__('Fecha de fin')" />
                            <x-text-input id="end_date-create" name="end_date" type="date" class="mt-1 block w-full" :value="$isCreating ? old('end_date') : ''" />
                            <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('end_date') : []" />
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="is_current-create" name="is_current" type="checkbox" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500" @checked($isCreating && old('is_current'))>
                        <x-input-label for="is_current-create" :value="__('Es mi puesto actual')" />
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
