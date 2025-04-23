<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\CustomerServices;
use Filament\Widgets\Widget;



class ServiceOverview extends Widget
{
    protected static string $view = 'livewire.service-overview';
    protected static string $pollingInterval = '5s';
    

    public function getViewData(): array
    {
        $userId = auth()->id();
    
        $services = CustomerServices::where('customer_id', $userId)
            ->latest()
            ->limit(1)
            ->get();
    
        return [
            'services' => $services,
            'serviceCount' => $services->count(),
        ];
    }
    


}
