<x-filament-panels::page>
    <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <h2 class="text-xl font-bold mb-4">Invite Friends</h2>
        
        <div class="mb-6">
            <p class="mb-4"><strong>🔥 Get rewarded!</strong> Share your unique referral link and earn <strong>5% bonus</strong> every time a friend signs up and starts a project. The more you share, the more you earn — don’t miss out!</p>
            
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                @php
                    $queryString = parse_url($this->getReferralLink(), PHP_URL_QUERY);
                    parse_str($queryString ?? '', $queryParams);
                    $refValue = $queryParams['ref'] ?? null;
                @endphp
                @if(!empty($refValue))
                <div class="relative flex-1">
                    <input 
                    type="text" 
                    readonly 
                    value="{{ $this->getReferralLink() }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                >
                </div>
               
                    <button
                        x-data="{}"
                        x-on:click="
                            navigator.clipboard.writeText('{{ $this->getReferralLink() }}');
                            $wire.copyToClipboard();
                        "
                        type="button"
                        class="inline-flex items-center justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Copy Link
                    </button>
                @else
                    <button
                        x-data="{}"
                        x-on:click="window.location.reload()"
                        type="button"
                        class="inline-flex items-center justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                @endif
            </div>
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mt-3">
            <h3 class="font-medium text-lg mb-2">How it works</h3>
            <ol class="list-decimal pl-5 space-y-2">
                <li>Share your unique referral link with friends</li>
                <li>When they sign up using your link and starts a project, you'll get a 5% bonus.</li>
                <li>You'll receive rewards for each successful referral</li>
            </ol>
        </div>
    </div>
    {{ $this->table }}
</x-filament-panels::page>