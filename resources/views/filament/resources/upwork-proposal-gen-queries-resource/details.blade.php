{{-- resources/views/filament/resources/upwork-proposal-gen-queries-resource/details.blade.php --}}

<div class="space-y-6">
    {{-- Project Information Section --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <x-heroicon-o-information-circle class="w-5 h-5 mr-2 text-blue-500" />
            Project Information
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User Name</label>
                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 rounded-md px-3 py-2 border">
                    {{ $record->user->name ?? 'N/A' }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Created At</label>
                <p class="text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 rounded-md px-3 py-2 border">
                    {{ $record->created_at->format('M d, Y H:i A') }}
                </p>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project Description</label>
            <div class="bg-white dark:bg-gray-700 rounded-md p-4 border max-h-32 overflow-y-auto">
                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->project_description }}</p>
            </div>
        </div>

        @if($record->portfolio)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Portfolio/Links</label>
            <div class="bg-white dark:bg-gray-700 rounded-md p-4 border max-h-24 overflow-y-auto">
                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->portfolio }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Generated Proposal Section --}}
    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <x-heroicon-o-document-text class="w-5 h-5 mr-2 text-green-500" />
            Generated Proposal
        </h3>
        
        <div class="bg-white dark:bg-gray-700 rounded-md p-4 border">
            <div class="prose prose-sm max-w-none dark:prose-invert max-h-96 overflow-y-auto">
                @if($record->AI_result)
                    {!! nl2br(strip_tags($record->AI_result, '<br><br/>')) !!}
                @else
                    <p class="text-gray-500 italic">No proposal generated yet.</p>
                @endif
            </div>
        </div>
        
        {{-- Copy to Clipboard Button --}}
        @if($record->AI_result)
        {{-- <div class="mt-4 flex justify-end">
            <x-filament::button
                color="success"
                size="sm"
                icon="heroicon-o-clipboard"
                onclick="copyToClipboard()"
            >
                Copy Proposal
            </x-filament::button>
        </div> --}}
        @endif
    </div>

    {{-- Error Section (if any) --}}
    @if($record->error)
    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-4 flex items-center">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 mr-2 text-red-500" />
            Error Information
        </h3>
        
        <div class="bg-white dark:bg-gray-700 rounded-md p-4 border border-red-200 dark:border-red-700">
            <p class="text-sm text-red-800 dark:text-red-200 whitespace-pre-wrap">{{ $record->error }}</p>
        </div>
    </div>
    @endif
</div>

{{-- JavaScript for Copy to Clipboard --}}
@if($record->AI_result)
@endif