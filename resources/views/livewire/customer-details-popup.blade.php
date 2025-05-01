<div class="w-full">
    
    {{-- this will only run if user have no projects started --}}
    @if($showModal)
        <div
            x-data="{}"
            x-init="$nextTick(() => $dispatch('open-modal', { id: 'customer-details-modal' }))"
        ></div>
    @endif
    {{-- this runs always enables button to show model --}}
    <div
    x-data="{}"
    x-init="window.addEventListener('open-customer-modal', () => {
        $dispatch('open-modal', { id: 'customer-details-modal' });
        $wire.showModal = true;
    })"
    ></div>

    {{-- The modal component --}}
    <x-filament::modal
        id="customer-details-modal"
        :close-by-clicking-away="true"
        display-classes="block"
        x-on:close="$wire.showModal = false"
        class="custom-wide-modal"
        width="3xl"

    >
        <x-slot name="header">
            <h2 class="text-lg font-bold">Start A New Project </h2>
        </x-slot>
        

        {{-- <x-slot name="content"> --}}
            <div class="p-4">
                {{ $this->form }}
            </div>
        {{-- </x-slot> --}}

        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">
                <x-filament::button 
                    x-on:click="$dispatch('close-modal', { id: 'customer-details-modal' })"
                    color="gray"
                >
                    Cancel
                </x-filament::button>
                
                <x-filament::button 
                    wire:click="submit" 
                    color="primary" 
                    icon="heroicon-o-check"
                >
                    Submit
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>
</div>