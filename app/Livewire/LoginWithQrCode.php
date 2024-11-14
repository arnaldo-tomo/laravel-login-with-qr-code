<?php

namespace App\Livewire;

use App\Models\SignInRequest;
use App\Models\User;
use Carbon\Carbon;
use Crypt;
use Illuminate\View\View;
use Livewire\Component;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LoginWithQrCode extends Component
{
    public bool $active = false;
    public ?string $code = null;
    protected ?Carbon $activeUntil = null;

    public function render(): View
    {
        if ($this->active && $this->code) {
            $this->pollLogin();
        }

        return view('livewire.login-with-qr-code', [
            'qrCode' => $this->code,
            'activeUntil' => $this->activeUntil,
        ]);
    }

    public function generateCode(): void
    {
        $token = Uuid::uuid4();

        session()->put('token', Crypt::encrypt($token));

        $signInRequest = SignInRequest::create([
            'token' => $token,
            'expires_at' => now()->addMinutes(5),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->put('signInId', Crypt::encrypt($signInRequest->id));

        $this->active = true;
        $this->activeUntil = now()->addMinutes(5);

        $this->code = base64_encode(
            QrCode::size(400)
                ->format('png')
                ->generate(
                    route('qr.verifyLogin', [
                        'id' => Crypt::encrypt($signInRequest->id),
                        'token' => $token
                    ])
                )
        );
    }

    public function calculateRemainingTime(string $activeUntil): float
    {
        return now()->diffInSeconds($activeUntil);
    }

    public function pollLogin(): void
    {
        $signInRequest = SignInRequest::query()
            ->where('id', Crypt::decrypt(session('signInId')))
            ->where('token', Crypt::decrypt(session('token')))
            ->where('expires_at', '>', now())
            ->first();


        if ($signInRequest && $signInRequest->approved) {
            auth()->login(User::find($signInRequest->user_id));

            request()->session()->regenerate();

            request()->user()
                ->userSessions()
                ->create([
                    'session_id' => request()->session()->getId(),
                    'ip_address' => request()->getClientIp(),
                    'user_agent' => request()->userAgent(),
                    'last_activity' => now()
                ]);

            $this->redirect(route('dashboard'));
        }
    }
}
