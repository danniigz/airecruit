@php
    $openForm = old('_form');
@endphp

<section>
    <details class="group" open>
        <summary class="flex items-center justify-between cursor-pointer list-none py-3">
            <h2 class="text-lg font-medium text-slate-900">{{ __('Certificaciones') }}</h2>
            <span class="text-slate-400 text-sm group-open:hidden">{{ __('Mostrar') }}</span>
            <span class="text-slate-400 text-sm hidden group-open:inline">{{ __('Ocultar') }}</span>
        </summary>

        <div class="mt-4 space-y-4">
            @if (session('status') === 'certification-created')
                <x-flash-message>{{ __('Certificación añadida.') }}</x-flash-message>
            @elseif (session('status') === 'certification-updated')
                <x-flash-message>{{ __('Certificación actualizada.') }}</x-flash-message>
            @elseif (session('status') === 'certification-deleted')
                <x-flash-message>{{ __('Certificación eliminada.') }}</x-flash-message>
            @endif

            @forelse ($profile->certifications as $certification)
                <div class="border border-slate-200 rounded-md p-4 flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900">{{ $certification->name }}</p>
                        <p class="text-sm text-slate-500">
                            @if ($certification->issuer){{ $certification->issuer }}@endif
                            @if ($certification->issuer && $certification->issue_date) &middot; @endif
                            @if ($certification->issue_date){{ $certification->issue_date->format('m/Y') }}@endif
                        </p>
                        @if ($certification->credential_url)
                            <a href="{{ $certification->credential_url }}" target="_blank" rel="noopener noreferrer" class="text-sm text-brand-600 hover:underline break-all">{{ $certification->credential_url }}</a>
                        @endif
                    </div>

                    <div class="flex gap-2 shrink-0">
                        <x-secondary-button type="button" data-modal-open="certification-edit-{{ $certification->id }}">
                            {{ __('Editar') }}
                        </x-secondary-button>

                        <form method="post" action="{{ route('profile.certifications.destroy', $certification) }}" onsubmit="return confirm('{{ __('¿Eliminar esta certificación?') }}');">
                            @csrf
                            @method('delete')
                            <x-danger-button>{{ __('Eliminar') }}</x-danger-button>
                        </form>
                    </div>
                </div>

                @php $isEditingThis = $openForm === 'certification-edit-'.$certification->id; @endphp
                <x-modal name="certification-edit-{{ $certification->id }}" :show="$isEditingThis">
                    <form method="post" action="{{ route('profile.certifications.update', $certification) }}" class="p-6 space-y-4">
                        @csrf
                        @method('patch')
                        <input type="hidden" name="_form" value="certification-edit-{{ $certification->id }}">

                        <h3 class="text-lg font-medium text-slate-900">{{ __('Editar certificación') }}</h3>

                        <div>
                            <x-input-label for="name-cert-edit-{{ $certification->id }}" :value="__('Nombre')" />
                            <x-text-input id="name-cert-edit-{{ $certification->id }}" name="name" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('name') : $certification->name" required />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('name') : []" />
                        </div>

                        <div>
                            <x-input-label for="issuer-edit-{{ $certification->id }}" :value="__('Entidad emisora')" />
                            <x-text-input id="issuer-edit-{{ $certification->id }}" name="issuer" type="text" class="mt-1 block w-full" :value="$isEditingThis ? old('issuer') : $certification->issuer" />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('issuer') : []" />
                        </div>

                        <div>
                            <x-input-label for="issue_date-edit-{{ $certification->id }}" :value="__('Fecha de obtención')" />
                            <x-text-input id="issue_date-edit-{{ $certification->id }}" name="issue_date" type="date" class="mt-1 block w-full" :value="$isEditingThis ? old('issue_date') : $certification->issue_date?->format('Y-m-d')" />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('issue_date') : []" />
                        </div>

                        <div>
                            <x-input-label for="credential_url-edit-{{ $certification->id }}" :value="__('URL de la credencial')" />
                            <x-text-input id="credential_url-edit-{{ $certification->id }}" name="credential_url" type="url" class="mt-1 block w-full" :value="$isEditingThis ? old('credential_url') : $certification->credential_url" />
                            <x-input-error class="mt-2" :messages="$isEditingThis ? $errors->get('credential_url') : []" />
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <x-secondary-button type="button" data-modal-close>{{ __('Cancelar') }}</x-secondary-button>
                            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                        </div>
                    </form>
                </x-modal>
            @empty
                <div class="border border-dashed border-slate-300 rounded-md p-4 text-center text-sm text-slate-500">
                    {{ __('Todavía no has añadido certificaciones.') }}
                </div>
            @endforelse

            <x-secondary-button type="button" data-modal-open="certification-create">
                {{ __('Añadir certificación') }}
            </x-secondary-button>

            @php $isCreating = $openForm === 'certification-create'; @endphp
            <x-modal name="certification-create" :show="$isCreating">
                <form method="post" action="{{ route('profile.certifications.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="certification-create">

                    <h3 class="text-lg font-medium text-slate-900">{{ __('Añadir certificación') }}</h3>

                    <div>
                        <x-input-label for="name-cert-create" :value="__('Nombre')" />
                        <x-text-input id="name-cert-create" name="name" type="text" class="mt-1 block w-full" :value="$isCreating ? old('name') : ''" required />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('name') : []" />
                    </div>

                    <div>
                        <x-input-label for="issuer-create" :value="__('Entidad emisora')" />
                        <x-text-input id="issuer-create" name="issuer" type="text" class="mt-1 block w-full" :value="$isCreating ? old('issuer') : ''" />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('issuer') : []" />
                    </div>

                    <div>
                        <x-input-label for="issue_date-create" :value="__('Fecha de obtención')" />
                        <x-text-input id="issue_date-create" name="issue_date" type="date" class="mt-1 block w-full" :value="$isCreating ? old('issue_date') : ''" />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('issue_date') : []" />
                    </div>

                    <div>
                        <x-input-label for="credential_url-create" :value="__('URL de la credencial')" />
                        <x-text-input id="credential_url-create" name="credential_url" type="url" class="mt-1 block w-full" :value="$isCreating ? old('credential_url') : ''" />
                        <x-input-error class="mt-2" :messages="$isCreating ? $errors->get('credential_url') : []" />
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
