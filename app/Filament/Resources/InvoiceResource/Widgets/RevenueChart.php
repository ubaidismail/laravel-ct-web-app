<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\invoices;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Support\RawJs;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue in USD';

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
        $invoices = invoices::whereNotNull('paid_date_formatted')->get();

        // Group invoices by month
        $invoices = $invoices->filter(function ($invoice) use ($currentYear) {
            return Carbon::parse($invoice->paid_date_formatted)->year == $currentYear;
        });
        $grouped = $invoices->groupBy(function ($invoice) {
            return Carbon::parse($invoice->paid_date_formatted)->format('Y-m');
        });

        foreach ($grouped as $month => $items) {
            $months->push(Carbon::createFromFormat('Y-m', $month)->format('M Y'));
            $revenues->push($items->sum('total_amount')); // or 'paid_amount' if you have that fieldre
        }
        // need to concat dollor sign in the revenue

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Sales in USD',
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
                            callback: (value) => '$' + value,
                        },
                    },
                },
            }
        JS);
    }
    public function getDescription(): ?string
    {
        return 'This chart shows the monthly revenue in USD for the year ' . Carbon::now()->year;
    }
    public static function canView(): bool
    {
        return auth()->user() && auth()->user()->user_role === 'admin';
    }
}
