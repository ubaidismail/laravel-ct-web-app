<x-filament-widgets::widget >
    {{-- class="!w-1/3 !max-w-sm --}}
    <x-filament::section>
        {{-- Widget content --}}
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('New Customers') }}
            </div>
            <div class="text-sm text-gray-900 dark:text-gray-100">
                <div x-data x-tooltip="'Customer acquisition from last month'" class="flex justify-between cursor-help">


                     @if($trend == 'up') 
                        <x-heroicon-o-arrow-trending-up class="w-4 h-4" />  +
                    @elseif($trend == 'down')
                        <x-heroicon-o-arrow-trending-down class="w-4 h-4" /> -
                    @endif
                    
                    <p class="">{{ $percentage_change }}%</p>

                </div>
            </div>
        </div>
        <p class="mt-3">{{ $current_customers }}</p>
        <p class="flex">
            {{ucfirst($trend)}} {{ $percentage_change }}% this month &nbsp;  
            @if($trend == 'up') 
            <x-heroicon-o-arrow-trending-up class="w-4 h-4 -bottom-5" /> 
        @elseif($trend == 'down')
            <x-heroicon-o-arrow-trending-down class="w-5 h-5 -bottom-5" />
        @endif
    </p>
    <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ $status_message }}
    </div>
    </x-filament::section>
</x-filament-widgets::widget>
