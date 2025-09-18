<?php

namespace App\Filament\Widgets;

use App\Models\invoices;
use App\Models\CompanySalesTargets;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class SalesProgress extends Widget
{
    protected static string $view = 'filament.widgets.sales-progress';
    
    protected int | string | array $columnSpan = 'full';
    
    public function getCurrentMonthSales(): float
    {
        $startOfMonth = Carbon::now()->startOfMonth()->startOfDay();
        $endOfMonth = Carbon::now()->endOfMonth()->endOfDay();

        // Paid invoices recognized by paid_date_formatted within the month
        $sales = invoices::query()
            ->whereBetween('paid_date_formatted', [$startOfMonth, $endOfMonth])
            ->sum('sub_total');

        return (float) $sales;
    }
    
    public function getCurrentMonthTarget(): float
    {
        $now = Carbon::now();
        
        // Match by month & year to handle dates stored as full dates (e.g., 2025-09-01)
        $targetValue = CompanySalesTargets::query()
            ->whereMonth('period', $now->month)
            ->whereYear('period', $now->year)
            ->value('sales_target');

        return $targetValue ? (float) $targetValue : 0.0;
    }
    
    public function getSalesProgressPercentage(): float
    {
        $sales = $this->getCurrentMonthSales();
        $target = $this->getCurrentMonthTarget();
        
        if ($target <= 0) {
            return 0;
        }
        
        return number_format(min(($sales / $target) * 100, 100) , 2);
    }
    
    public function getProgressColor(): string
    {
        $percentage = $this->getSalesProgressPercentage();
        
        if ($percentage >= 100) {
            return 'success';
        } elseif ($percentage >= 75) {
            return 'warning';
        } elseif ($percentage >= 50) {
            return 'info';
        } else {
            return 'danger';
        }
    }
    
    public function getFormattedSales(): string
    {
        return number_format($this->getCurrentMonthSales(), 2);
    }
    
    public function getFormattedTarget(): string
    {
        return number_format($this->getCurrentMonthTarget(), 2);
    }
    
    public function getCurrentMonthName(): string
    {
        return Carbon::now()->format('F Y');
    }
}
