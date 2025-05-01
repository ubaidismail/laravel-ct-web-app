<?php
namespace App\Livewire\Auth;

use App\Models\ReferalToken;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReferalJoined;

class Register extends BaseRegister
{
    public $referralCode;
    public function mount(): void
    {
        parent::mount();

        $referralCode = $this->getReferralCode();

        if (! ReferalToken::isValid($referralCode)) {
            abort(403, 'Invalid or missing referral code.');
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
            TextInput::make('referral_code')
                ->label('Referral Code')
                ->default(fn () => request()->get('ref', ''))
                ->readOnly()
                ->required()
                // ->dehydrated(false),
        ]);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(10);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title('Too many requests. Try again later.')
                ->danger()
                ->send();
            return null;
        }

        // if (! ReferalToken::isValid($this->getReferralCode())) {
        //     throw ValidationException::withMessages([
        //         'referral_code' => 'Invalid referral code',
        //     ]);
        // }

        $data = $this->form->getState();
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'user_role' => 'customer',
            'address' => '',
            'phone' => '',
            'project' => '',
            'company' => '',
            'currency' => '$',
            'pass_for_admin_view' => $data['password'],
            'total_amount_paid' => 0,
            'status' => 1,
            'referal_code' => $data['referral_code'],
        ]);
        

        // ReferalToken::markAsUsed($this->getReferralCode());

        event(new Registered($user));
        

        $this->sendMail($user);
        
        Filament::auth()->login($user);
        session()->regenerate();



        return app(RegistrationResponse::class);
    }

    protected function getReferralCode(): string
    {
        // var_dump(request()->get('ref'));
        return request()->get('ref', '');
    }

    public function sendMail($user): void
    {

        // Implement your email sending logic here
        // For example, you can use Laravel's Mail facade to send an email

        Mail::to($user->email)->send(new ReferalJoined($user));
        
        
    }
}
