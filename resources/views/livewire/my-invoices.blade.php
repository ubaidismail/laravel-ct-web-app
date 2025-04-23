{{-- <x-filament-widgets::widget> --}}
    <x-filament::section>
    
        <x-filament::card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-xl font-bold text-gray-800">My Invoices</div>
                </div>
                <div class="text-3xl font-extrabold text-primary-600">
                    {{ $invoiceCount < 10 ? '0' . $invoiceCount : $invoiceCount }}
                </div>
                
            </div>
    
            <div class="flex items-center justify-between">
                <div>
                    @php $firstShown = false; @endphp
                @forelse ($invoices as $invoice)

                {{-- display only one --}}
                    @if (!$firstShown)
                    <div class="py-2 text-sm text-white-700">
                        <span class="font-medium">{{ $invoice->project_name }}</span> —
                        <span class="capitalize text-sm text-gray-900
                        ">{{ $invoice->invoice_type }}</span>
                    </div>
                    @php $firstShown = true; @endphp
                    @endif

                @empty
                    <div class="text-sm text-gray-500 py-2">No invoices found.</div>
                @endforelse
                </div>
                
                <div>
                    <x-filament::button
                        color="primary"
                        tag="a"
                        href="{{ route('filament.customer.pages.my-inovices')}}" 
                        icon="heroicon-m-arrow-right">
                        View All
                    </x-filament::button>
                </div>
            </div>
        </x-filament::card>
    </x-filament::section>
    {{-- </x-filament-widgets::widget> --}}
    