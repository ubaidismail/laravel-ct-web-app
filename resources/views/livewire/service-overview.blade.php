{{-- <x-filament-widgets::widget> --}}
    <x-filament::section>
        {{-- <div class="text-3xl font-semibold mb-3">My Services</div> --}}
    
        <x-filament::card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-xl font-bold text-gray-800">My Services</div>

                </div>
                <div class="text-3xl font-extrabold text-primary-600">
                    {{ $serviceCount < 10 ? '0' . $serviceCount : $serviceCount }}
                </div>
                
            </div>


            <div class="flex items-center justify-between">
                <div>
                    @forelse ($services as $service)
                    <div class="py-2 text-sm text-white-700">
                        <span class="font-medium">{{ $service->service_name }}</span> —
                        <span class="capitalize text-sm text-gray-900
                        ">{{ $service->service_status }}</span>
                    </div>
                @empty
                    <div class="text-sm text-gray-500 py-2">No services found.</div>
                @endforelse
                </div>
                
                <div>
                    <x-filament::button
                        color="primary"
                        tag="a"
                        href="{{ route('filament.customer.pages.my-services')}}" 
                        icon="heroicon-m-arrow-right">
                        View All
                    </x-filament::button>
                </div>
            </div>

        </x-filament::card>
    </x-filament::section>
    {{-- </x-filament-widgets::widget> --}}
    