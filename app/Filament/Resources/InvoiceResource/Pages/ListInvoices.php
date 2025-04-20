<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;



class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
    public function mount(): void
    {
        if (auth()->user()->user_role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('createInvoice')
                ->label('New Invoice')
                ->url(fn () => url('/create-invoice')) // Adjust path if needed
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];


    }
//     protected function getTableActions(): array
// {
//     return [
//         EditAction::make()
//             ->url(fn ($record) => url('/edit-invoice/' . $record->id)), // custom URL
//     ];
// }


protected function getTableActions(): array
{
    return [
        Action::make('edit')
            ->label('Edit')
            ->icon('heroicon-o-pencil')
            ->url(fn ($record) => url('/edit-invoice/' . $record->id))
    ];
}


}
