<x-filament-panels::page>
    <h1>Generate Insights</h1>
    {{ $this->form }}
    <x-filament::button
    wire:click="generateInsights"
    :loading="$isGenerating"
    loading-text="Generating Insights..."
    >Generate Insights</x-filament::button>
</x-filament-panels::page>
