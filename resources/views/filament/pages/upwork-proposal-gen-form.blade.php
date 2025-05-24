<x-filament-panels::page>
    <form wire:submit.prevent="create" class="space-y-6">
        {{ $this->form }}
        <x-filament::button type="submit" wire.loading.attr="disabled" wire.target="create"> 
            <span wire:loading.remove wire:target="create">
                Generate Proposal
            </span>
            
            <span wire:loading wire:target="create" class="flex items-center gap-x-2">
                <x-filament::loading-indicator class="h-4 w-4" />
            </span>
            
        </x-filament::button>
        

    </form>

    @if ($result)
        <div class="mt-6 border p-4 rounded relative" style="background: #4f505a96;">
            <h3 class="font-bold text-lg mb-2">
                @if(empty($result))
                    Error
                @else
                    Generated Proposal
                @endif
            </h3>
            {{-- proper markdown response --}}
            <div class="prose text-white">
                {{-- result empty show error var --}}
                @if (empty($result))
                    <p class="text-red-500"> {!! $error !!} </p>
                @else
                    {!! $result !!}
                @endif
                {{-- {!! $result !!} --}}
            </div>
            <div class="mt-4">
                <x-filament::button 
                style="position: absolute; right: 0; top: 0;"
                x-data="{
                    copied: false,
                    copyToClipboard() {
                        const text = document.querySelector('.prose').innerText;
                        navigator.clipboard.writeText(text)
                            .then(() => {
                                this.copied = true;
                                setTimeout(() => {
                                    this.copied = false;
                                }, 2000);
                            })
                            .catch(err => {
                                console.error('Failed to copy text: ', err);
                            });
                    }
                }"
                x-on:click="copyToClipboard"
                color="secondary"
            >
                <span x-show="copied">Copied!</span>
                <span x-show="!copied">
                    @svg('heroicon-o-clipboard', 'w-5 h-5 mr-1 -ml-1')
                </span>
            </x-filament::button>
            </div>
        </div>
    @endif
</x-filament-panels::page>