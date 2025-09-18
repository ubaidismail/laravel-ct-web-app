<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Sales Progress - {{ $this->getCurrentMonthName() }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Track your monthly sales performance
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $this->getSalesProgressPercentage() }}%
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        of target achieved
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="space-y-3">
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                    <span>Progress</span>
                    <span>{{ $this->getFormattedSales() }} / {{ $this->getFormattedTarget() }}</span>
                </div>
                
                <div class="relative">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div 
                            class="h-full transition-all duration-500 ease-out rounded-full
                                @if($this->getProgressColor() === 'success') bg-green-500
                                @elseif($this->getProgressColor() === 'warning') bg-yellow-500
                                @elseif($this->getProgressColor() === 'info') bg-blue-500
                                @else bg-primary-500
                                @endif"
                            style="width: {{ $this->getSalesProgressPercentage() }}%"
                        ></div>
                    </div>
                    
                    <!-- Progress indicator dots -->
                    <div class="absolute top-0 left-0 w-full h-4 flex justify-between items-center pointer-events-none">
                        <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                        <div class="w-1 h-1 bg-gray-400 dark:bg-gray-500 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Current Sales</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">
                        ${{ $this->getFormattedSales() }}
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Monthly Target</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">
                        ${{ $this->getFormattedTarget() }}
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Remaining</div>
                    <div class="text-xl font-semibold text-gray-900 dark:text-white">
                        ${{ number_format(max(0, $this->getCurrentMonthTarget() - $this->getCurrentMonthSales()), 2) }}
                    </div>
                </div>
            </div>

            <!-- Status Message -->
            <div class="text-center">
                @if($this->getSalesProgressPercentage() >= 100)
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Target Achieved! 🎉
                    </div>
                @elseif($this->getSalesProgressPercentage() >= 75)
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Almost there! Keep going! 💪
                    </div>
                @elseif($this->getSalesProgressPercentage() >= 50)
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Good progress! 📈
                    </div>
                @else
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Need to accelerate! 🚀
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
