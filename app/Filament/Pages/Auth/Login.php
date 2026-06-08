<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Illuminate\Auth\Events\Failed;

class Login extends BaseLogin
{
    public bool $isWaitingApproval = false;

    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (\DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();
            return null;
        }

        $this->isWaitingApproval = false;

        $data = $this->form->getState();

        /** @var \Illuminate\Auth\SessionGuard $authGuard */
        $authGuard = Filament::auth();

        $authProvider = $authGuard->getProvider();
        $credentials = $this->getCredentialsFromFormData($data);

        $user = $authProvider->retrieveByCredentials($credentials);

        // Jika user tidak ditemukan atau password salah
        if ((! $user) || (! $authProvider->validateCredentials($user, $credentials))) {
            $this->userUndertakingMultiFactorAuthentication = null;
            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        // Kredensial benar, cek apakah bisa akses panel (approval check)
        if ($user instanceof FilamentUser && !$user->canAccessPanel(Filament::getCurrentOrDefaultPanel())) {
            // User benar tapi belum di-approve
            $this->isWaitingApproval = true;
            return null; // Hentikan proses login, biarkan form me-render ulang dengan style hijau
        }

        // Lanjut login jika normal
        // Karena kita mengubah cara auth, panggil login() secara manual
        $authGuard->login($user, $data['remember'] ?? false);
        session()->regenerate();

        return app(\Filament\Auth\Http\Responses\Contracts\LoginResponse::class);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::auth/pages/login.form.email.label'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(fn () => $this->isWaitingApproval ? [
                'style' => 'border-color: #22c55e; box-shadow: 0 0 0 1px #22c55e;',
            ] : [])
            ->helperText(fn () => $this->isWaitingApproval ? new HtmlString('<span style="color: #22c55e; font-weight: 500;">waiting approval</span>') : null);
    }
}
