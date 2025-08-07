<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class NewProspectCount extends Widget
{
    protected static string $view = 'filament.widgets.new-prospect-count';

    protected static ?string $heading = 'New Leads/Prospects';

    public function getViewData(): array
    {
        // Current period (last 30 days)
        $currentPeriodStart = now()->subDays(30);
        $currentPeriodEnd = now();
        
        // Previous period (30 days before that)
        $previousPeriodStart = now()->subDays(60);
        $previousPeriodEnd = now()->subDays(30);
        
        // Get counts for both periods
        $currentCustomers = User::where('user_role', 'prospect')
        ->whereBetween('created_at', [$currentPeriodStart, $currentPeriodEnd])->count();
        $previousCustomers = User::where('user_role', 'prospect')
        ->whereBetween( 'created_at', [$previousPeriodStart, $previousPeriodEnd])->count();
        
        // Calculate percentage change
        $percentageChange = $this->calculatePercentageChange($currentCustomers, $previousCustomers);
        
        // Determine trend
        $trend = $this->getTrend($percentageChange);
        
        return [
            'current_customers' => $currentCustomers,
            'previous_customers' => $previousCustomers,
            'percentage_change' => $percentageChange,
            'trend' => $trend,
            'status_message' => $this->getStatusMessage($trend, abs($percentageChange)),
        ];
    }
    
    private function calculatePercentageChange(int $current, int $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }
    
    private function getTrend(float $percentageChange): string
    {
        if ($percentageChange > 0) {
            return 'up';
        } elseif ($percentageChange < 0) {
            return 'down';
        } else {
            return 'stable';
        }
    }
    
    private function getStatusMessage(string $trend, float $percentage): string
    {
        switch ($trend) {
            case 'up':
                if ($percentage >= 20) {
                    return 'Excellent growth!';
                } elseif ($percentage >= 10) {
                    return 'Good progress';
                } else {
                    return 'Slight improvement';
                }
                
            case 'down':
                if ($percentage >= 20) {
                    return 'Acquisition needs attention';
                } elseif ($percentage >= 10) {
                    return 'Declining trend';
                } else {
                    return 'Minor decrease';
                }
                
            default:
                return 'No change this period';
        }
    }

}
