<?php

namespace App\Filament\Pages;

use App\Filament\Resources\InvoiceResource\Widgets\AnnualRevenue;
use App\Filament\Resources\InvoiceResource\Widgets\RevenueChart;
use App\Filament\Resources\InvoiceResource\Widgets\RevenueChartInPKR;
use App\Filament\Widgets\NewCustomerCount;

use Illuminate\Contracts\Support\Htmlable;



class Dashboard extends \Filament\Pages\Dashboard
{

    public function mount()
    {
        $this->users = \App\Models\User::latest()->take(5)->get();
    }

    public function getViewData(): array
    {
        return [
            'users' => $this->users,
        ];
    }
    public function getSubheading(): string|Htmlable|null
    {
        $user = auth()->user();
        if ($user) {
            return 'Welcome, ' . $user->name;
        }
    }
    public function getColumns(): int
    {
        return 3; // MUST set this first
    }

    protected function getFooterWidgets(): array
    {
        if (auth()->user() && auth()->user()->user_role === 'admin') {
            return [

                // NewCustomerCount::class,
                RevenueChart::class,
                RevenueChartInPKR::class,
                AnnualRevenue::class,
            ];
        }
    }
    public function getWidgets(): array
    {
        // user isrole is not admin
        // auth()->user() && auth()->user()->user_role === 'admin   
        if (auth()->user() && auth()->user()->user_role === 'admin') {
            return [

                NewCustomerCount::class,

            ];
        }
    }
   
}
