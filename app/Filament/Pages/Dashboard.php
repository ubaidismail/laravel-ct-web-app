<?php

namespace App\Filament\Pages;

use App\Models\User;



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

}