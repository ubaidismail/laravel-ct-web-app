<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\invoices;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Support\RawJs;

class AnnualRevenue extends ChartWidget
{
    protected static ?string $heading = 'Annual Revenue';
    protected static bool $isLazy = true;


    protected function getType(): string
    {
        return 'line'; // You can also use 'bar'
    }

    protected function getData(): array
    {
        $months = collect();
        $revenues = collect();
        // for current year
        $currentYear = Carbon::now()->year;
        // Fetch all invoices with a valid paid_date_formatted
        $invoices = invoices::whereNotNull('paid_date_formatted')
        ->Where('invoice_type', 'paid')->get()
        ->sortBy(function ($invoice) {
        return \Carbon\Carbon::parse($invoice->paid_date_formatted);
    });


        // Group invoices by month
        // $invoices = $invoices->filter(function ($invoice) use ($currentYear) {
        //     return Carbon::parse($invoice->paid_date_formatted)->year == $currentYear;
        // });
        $grouped = $invoices->groupBy(function ($invoice) {
            return Carbon::parse($invoice->paid_date_formatted)->format('Y');
        });

        foreach ($grouped as $month => $items) {
            $months->push(Carbon::createFromFormat('Y', $month)->format('Y'));
            $revenues->push($items->sum('total_amount'));
        }
        // need to concat dollor sign in the revenue
        return [
            'datasets' => [
                [
                    'label' => 'Annual Sales in $',
                    // add $ sign
                    'data' => $revenues->toArray(),
                    'borderColor' => '#3b82f6', // blue-500
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                    'pointBackgroundColor' => '#3b82f6',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointStyle' => 'circle',
                    'pointBorderWidth' => 2,
                    'pointHoverBorderWidth' => 2,
                    'pointHitRadius' => 10,

                    'pointStyle' => 'rectRot',
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
                    'pointBackgroundColor' => '#3b82f6',
                    'pointBorderColor' => '#fff',
                // fade bheaviour

                ],
            ],
            'labels' => $months->toArray(),

        ];
    }
    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    y: {
                        ticks: {
                            callback: (value) => '$ ' + value,
                        },
                    },
                },
            }
        JS);
    }
    public function getDescription(): ?string
    {
        // return 'This chart shows the monthly revenue in PKR for the year ' . Carbon::now()->year;
        return '';
    }
    public static function canView(): bool
    {
        return auth()->user() && auth()->user()->user_role === 'admin';
    }
}
