<?php

namespace App\Filament\CustomerPanel\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\ReferalToken;
use App\Models\CustomerServices;
use App\Models\RewardPoints;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;


class inviteFriend extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.customer-panel.pages.invite-friend';
    public static ?string $navigationLabel = 'Refer a Friend';
    public static ?string $title = 'Refer a Friend';

    public ?string $referralCode = null;

    // public static function shouldRegisterNavigation(): bool
    // {
    //     // return CustomerServices::where('customer_id', Auth::id())->exists();
    // }

    protected function getTableQuery(): Builder
    {
        return RewardPoints::query()
            ->where('referrer_user_id', Auth::id());
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('project_quote_id')->label('Project ID'),
            TextColumn::make('referrer_user_id')->label('Referrer ID'),
            TextColumn::make('referal_type')->label('Token'),
            TextColumn::make('percent_markup')->label('Percent Markup'),
            TextColumn::make('amount')->label('Amount')
            // calculate 5% of total project amount
                ->formatStateUsing(function (RewardPoints $record) {
                    return str_replace('$' , '', $record->amount) * $record->percent_markup / 100;
                }),
            TextColumn::make('status')->label('Status')
            ->badge()
            ->formatStateUsing(function (RewardPoints $record) {
                return ucfirst($record->status);
            })
            ->color(function ($state) {
                return $state ==  'pending' || $state == 'cancelled' ||  $state == 'started' ? 'warning' : 'success';
            })

        ];
    }
    
    public function mount()
    {
        $referralCode = ReferalToken::where('referrer_user_id', Auth::id())->first();
        if (!empty($referralCode)) {
            $this->referralCode = $referralCode->token ?? null;
        } else {
            $this->generateReferralCode(Auth::id());
        }
    }

    protected function generateReferralCode()
    {

        $referralCode = 'CT_' . Str::random(8);
        // save data in db
        $code = ReferalToken::create([
            'referrer_user_id' => Auth::id(),
            'token' => $referralCode,
            'expires_at' => now()->addDays(30), // Set expiration date
        ]);
        return $code;
    }

    public function getReferralLink()
    {
        return url('/register?ref=' . $this->referralCode);
    }

    public function copyToClipboard()
    {
        // This will trigger client-side copy action via JavaScript
        Notification::make()
            ->title('Referral link copied to clipboard!')
            ->success()
            ->send();
    }

  
    
}
