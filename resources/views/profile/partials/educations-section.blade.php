@php
    $openForm = old('_form');
@endphp

<section>
    <details class="group" open>
        <summary class="flex items-center justify-between cursor-pointer list-none py-3">
            <h2 class="text-lg font-medium text-slate-900">{{ __('Formación académica') }}</h2>
            <span class="text-slate-400 text-sm group-open:hidden">{{ __('Mostrar') }}</span>
            <span class="text-slate-400 text-sm hidden group-open:inline">{{ __('Ocultar') }}</span>
        </summary>

        <div class="mt-4 space-y-4">
            @if (session('status') === 'education-created')
                <x-flash-message>{{ __('Formación añadida.') }}</x-flash-message>
            @elseif (session('status') === 'education-updated')
                <x-flash-message>{{ __('Formación actualizada.') }}</x-flash-message>
            @elseif (session('status') === 'education-deleted')
                <x-flash-message>{{ __('Formación eliminada.') }}</x-flash-message>
            @endif

            @forelse ($profile->educations as $education)
                <div class="border border-slate-200 rounded-md p-4 flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900">{{ $education->degree }} · {{ $education->institution }}</p>
                        @if ($education->field_of_study)
                            <p class="text-sm text-slate-600">{{ $education->field_of_study }}</p>
                        @endif
                        <p class="text-sm text-slate-500">
                            {{ $education->start_date->format('m/Y') }}
                            &mdash;
                            {{ $education->end_date?->format('m/Y') ?? __('Actualidad') }}
                        </p>
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <x-secondary-button type="button" data-modal-open="education-edit-{{ $education->id }}">
                            {{ __('Editar') }}
                        </x-secondary-button>

                        <form method="post" action="{{ route('profile.educations.destroy', $education) }}" onsubmit="return confirm('{{ __('¿Eliminar esta formación?') }}');">
                            @csrf
                            @method('delete')
                            <x-danger-button>{{ __('Eliminar') }}</x-danger-button>
                        </form>
                    </div>
                </div>

                @php $isEditingThis = $openForm === 'education-edit-'.$education->id; @endphp
                <x-modal name="education-edit-{{ $education->id }}" :show="$isEditingThis">
                    <form method="post" action="{{ route('profile.educations.update', $education) }}" class="p-6 space-y-4">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="_form" value="education-edit-{{ $education->id }}">

                        <h3 class="text-lg font-medium text-slate-900">{{ __('Editar formación') }}</h3>

                        <div>
                            <x-input-label for="institution-edit-{{ $education->id }}" :value="__('Institución')" />
                            <x-text-input id="institution-edit-{{ $education->id }}" name="institution" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('institution') : $education->institution" required />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('institution') : []" />
                        </div>

                        <div>
                            <x-input-label for="degree-edit-{{ $education->id }}" :value="__('Título')" />
                            <x-text-input id="degree-edit-{{ $education->id }}" name="degree" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('degree') : $education->degree" required />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('degree') : []" />
                        </div>

                        <div>
                            <x-input-label for="field_of_study-edit-{{ $education->id }}" :value="__('Campo de estudio')" />
                            <x-text-input id="field_of_study-edit-{{ $education->id }}" name="field_of_study" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('field_of_study') : $education->field_of_study" />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('field_of_study') : []" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_date-edu-edit-{{ $education->id }}" :value="__('Fecha de inicio')" />
                                <x-text-input id="start_date-edu-edit-{{ $education->id }}" name="start_date" type="date" class="mt-1 block w-full" :value="$isEditingThis ? old('start_date') : $education->start_date->format('Y-m-d')" required />
                                <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('start_date') : []" />
                            </div>

                            <div>
                                <x-input-label for="end_date-edu-edit-{{ $education->id }}" :value="__('Fecha de fin')" />
                                <x-text-input id="end_date-edu-edit-{{ $education->id }}" name="end_date" type="date" class="mt-1 block w-full" :value="$isEditingThis ? old('end_date') : $education->end_date?->format('Y-m-d')" />
                                <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('end_date') : []" />
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <x-secondary-button type="button" data-modal-close>{{ __('Cancelar') }}</x-secondary-button>
                            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                        </div>
                    </form>
                </x-modal>
            @empty
                <div class="border border-dashed border-slate-300 rounded-md p-4 text-center text-sm text-slate-500">
                    {{ __('Todavía no has añadido formación académica.') }}
                </div>
            @endforelse

            <x-secondary-button type="button" data-modal-open="education-create">
                {{ __('Añadir formación') }}
            </x-secondary-button>

            @php $isCreating = $openForm === 'education-create'; @endphp
            <x-modal name="education-create" :show="$isCreating">
                <form method="post" action="{{ route('profile.educations.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="education-create">

                    <h3 class="text-lg font-medium text-slate-900">{{ __('Añadir formación') }}</h3>

                    <div>
                        <x-input-label for="institution-create" :value="__('Institución')" />
                        <x-text-input id="institution-create" name="institution" type="text" class="mt-1 block w-full" :value="$isCreating ? old('institution') : ''" required />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('institution') : []" />
                    </div>

                    <div>
                        <x-input-label for="degree-create" :value="__('Título')" />
                        <x-text-input id="degree-create" name="degree" type="text" class="mt-1 block w-full" :value="$isCreating ? old('degree') : ''" required />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('degree') : []" />
                    </div>

                    <div>
                        <x-input-label for="field_of_study-create" :value="__('Campo de estudio')" />
                        <x-text-input id="field_of_study-create" name="field_of_study" type="text" class="mt-1 block w-full" :value="$isCreating ? old('field_of_study') : ''" />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('field_of_study') : []" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="start_date-edu-create" :value="__('Fecha de inicio')" />
                            <x-text-input id="start_date-edu-create" name="start_date" type="date" class="mt-1 block w-full" :value="$isCreating ? old('start_date') : ''" required />
                            <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('start_date') : []" />
                        </div>

                        <div>
                            <x-input-label for="end_date-edu-create" :value="__('Fecha de fin')" />
                            <x-text-input id="end_date-edu-create" name="end_date" type="date" class="mt-1 block w-full" :value="$isCreating ? old('end_date') : ''" />
                            <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('end_date') : []" />
                        </div>
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
