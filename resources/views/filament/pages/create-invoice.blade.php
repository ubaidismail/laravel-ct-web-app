<x-filament-panels::page>

        <form wire:submit.prevent="create" class="space-y-6">
            {{ $this->form }}
            <x-filament::button type="submit">
                Create Invoice
            </x-filament::button>
        </form>

    
</x-filament-panels::page>
