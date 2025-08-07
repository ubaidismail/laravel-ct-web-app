<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit="askGemini">
            {{ $this->form }}
            
            <div class="flex justify-end mt-4">
                <x-filament::button 
                    type="submit"
                    color="primary"
                    size="lg"
                    :disabled="$loading"
                    icon="heroicon-o-sparkles"
                >
                    @if($loading)
                        <x-filament::loading-indicator class="h-4 w-4" />
                        Processing...
                    @else
                        Ask AI
                    @endif
                </x-filament::button>
            </div>
        </form>
        
        @if($loading)
            <div class="bg-blue-50 dark:bg-blue-950/20 rounded-lg p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center space-x-3">
                    <x-filament::loading-indicator class="h-5 w-5 text-blue-600" />
                    <span class="text-blue-700 dark:text-blue-300 font-medium">AI is analyzing your data...</span>
                </div>
            </div>
        @endif
        
        @if($response && !$loading)
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-sparkles class="h-6 w-6 text-purple-600" />
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">AI Insights</h3>
                    </div>
                </div>
                <div class="prose prose-gray dark:prose-invert max-w-none text-sm leading-relaxed">
                    {!! nl2br(e($response)) !!}
                </div>
            </div>
        @endif
        
        @if(!$response && !$loading)
            <div class="text-center py-12">
                <x-heroicon-o-chat-bubble-left-right class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Ready to help</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ask any question about your business data to get AI-powered insights.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>