<?php

namespace App\Filament\CustomerPanel\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\ReferalToken;
use App\Models\CustomerServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;


class inviteFriend extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.customer-panel.pages.invite-friend';
    public static ?string $navigationLabel = 'Refer a Friend';
    public static ?string $title = 'Refer a Friend';

    public ?string $referralCode = null;

    // public static function shouldRegisterNavigation(): bool
    // {
    //     // return CustomerServices::where('customer_id', Auth::id())->exists();
    // }

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
    

    // public function create() : void
    // {
       
    // }
    
}
