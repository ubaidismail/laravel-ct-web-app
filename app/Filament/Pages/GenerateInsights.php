<?php

namespace App\Filament\Pages;

use App\Models\AIInsightReport;
use App\Services\AIInsightsService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\FileUpload;

class GenerateInsights extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Generate AI Insights';

    protected static ?string $navigationGroup = 'Analytics';

    protected static string $view = 'filament.pages.generate-insights';

    public ?array $data = [];

    public ?array $generatedInsights = null;

    public bool $isGenerating = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Generate Business Insights')
                    ->description('Use AI to analyze your business data and generate actionable insights')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Analysis Type')
                            ->options([
                                'revenue_analysis' => 'Revenue Analysis',
                                'service_performance' => 'Service Performance',
                                'customer_insights' => 'Customer Insights',
                                'forecast' => 'Revenue Forecast',
                            ])
                            ->default('revenue_analysis')
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('date_range')
                            ->label('Date Range')
                            ->options([
                                '7_days' => 'Last 7 Days',
                                '30_days' => 'Last 30 Days',
                                '90_days' => 'Last 90 Days',
                                '365_days' => 'Last Year',
                            ])
                            ->default('30_days')
                            ->required(),

                        Forms\Components\Placeholder::make('description')
                            ->content(fn(Forms\Get $get): string => match ($get('type')) {
                                'revenue_analysis' => 'Analyze revenue trends, growth patterns, and identify opportunities to increase income.',
                                'service_performance' => 'Evaluate which services are most profitable and identify optimization opportunities.',
                                'customer_insights' => 'Understand customer behavior, segmentation, and retention patterns.',
                                'forecast' => 'Generate revenue forecasts with confidence intervals and risk assessment.',
                                default => 'Select an analysis type to see the description.'
                            }),
                        // file option to import data as csv an
                        FileUpload::make('add_your_csv_file')
                            ->label('Add Your Preferred CSV File To Get Insights')
                            ->acceptedFileTypes(['application/csv'])
                            ->maxSize(1024)
                            ->preserveFilenames()
                    ]),
            ])
            ->statePath('data');
    }

    public function generateInsights(): void
    {
        try {
            $this->isGenerating = true;

            $data = $this->form->getState();


            // Generate insights
            $aiService = app(AIInsightsService::class);
            $insights = $aiService->generateInsights($data['type'], $data['date_range']);
            if (!empty($insights)) {
                $report = AIInsightReport::create([
                    'type' => $data['type'],
                    'data' => [],
                    'generated_at' => now(),
                    'user_id' => Auth::id(),
                    'status' => 'generating'
                ]);
                
                // Update report

                $report->update([
                    'data' => $insights,
                    'status' => 'completed'
                ]);

                $this->generatedInsights = $insights;
                $this->isGenerating = false;

                Notification::make()
                    ->title('Insights Generated Successfully')
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            $this->isGenerating = false;

            if (isset($report)) {
                $report->update(['status' => 'failed']);
            }

            Notification::make()
                ->title('Failed to Generate Insights')
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw new \Exception($e->getMessage());
        }
    }

    public function clearInsights(): void
    {
        $this->generatedInsights = null;
    }
}
