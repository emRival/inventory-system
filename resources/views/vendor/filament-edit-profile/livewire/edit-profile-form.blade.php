<x-filament-panels::form wire:submit="updateProfile">
    {{ $this->form }}

    <div class="fi-form-actions">
        <div class="flex flex-row-reverse flex-wrap items-center gap-3 fi-ac">
            @php
                $canEdit = auth()->user()?->hasRole('super_admin');
            @endphp

            @if ($canEdit)
                <x-filament::button type="submit">
                    {{ __('filament-edit-profile::default.save') }}
                </x-filament::button>
            @endif

        </div>
    </div>
</x-filament-panels::form>
