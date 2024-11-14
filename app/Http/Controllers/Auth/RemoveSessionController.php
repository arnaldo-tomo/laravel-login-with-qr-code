<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserSession;

class RemoveSessionController extends Controller
{
    public function __invoke(UserSession $userSession)
    {
        abort_unless($userSession->user_id === auth()->id(), 404);

        $userSession->delete();

        return redirect()->back()
            ->with(['success' => 'Session removed successfully']);
    }
}
