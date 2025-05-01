<?php

namespace App\Filament\CustomerPanel\Pages;

use Filament\Pages\Dashboard;
use App\Models\User;
use Illuminate\Contracts\Support\Htmlable;
use App\Livewire\ServiceOverview;
use App\Livewire\MyInvoices;
use App\Livewire\CustomerDetailsPopup;


class CustomerDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    // public static function getNavigationLabel(): string
    // {
    //     return 'Customer Dashboard';
    // }
    public function getSubheading(): string|Htmlable|null
    {
        // show welcome message with user name
        $user = auth()->user();
        if ($user) {
            return 'Welcome, ' . ucfirst($user->name);
        }
    }
    public function getWidgets(): array
    {
        // user isrole is not admin
        // auth()->user() && auth()->user()->user_role === 'admin   
        if (auth()->user() && auth()->user()->user_role === 'customer') {
            
            return [
                ServiceOverview::class,
                MyInvoices::class,
                CustomerDetailsPopup::class,
            ];
        }
    }


}