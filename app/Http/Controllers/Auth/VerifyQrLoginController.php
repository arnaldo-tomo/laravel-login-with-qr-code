<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SignInRequest;
use Crypt;

class VerifyQrLoginController extends Controller
{
    public function __invoke(string $id, string $token)
    {
        $signInRequest = SignInRequest::query()
            ->where('expires_at', '>', now())
            ->where('id', Crypt::decrypt($id))
            ->where('token', $token)
            ->first();

        abort_unless($signInRequest, 404);

        $signInRequest->update([
            'user_id' => auth()->id(),
            'approved' => true,
            'approved_at' => now(),
            'approved_ip' => request()->ip(),
            'approved_user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('profile.edit');
    }
}
