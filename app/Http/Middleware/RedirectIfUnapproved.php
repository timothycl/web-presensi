<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as Middleware;
use Filament\Facades\Filament;
use Illuminate\Http\Exceptions\HttpResponseException;

class RedirectIfUnapproved extends Middleware
{
    protected function authenticate($request, array $guards): void
    {
        $guard = Filament::auth();

        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);
            return;
        }

        $this->auth->shouldUse(Filament::getAuthGuard());
        $user = $guard->user();

        // Check if user is not approved
        if ($user && $user->approval_status !== 'approved' && !$user->isAdmin()) {
            throw new HttpResponseException(redirect()->route('waiting-approval'));
        }

        // Proceed to default authenticate which handles other checks like canAccessPanel
        parent::authenticate($request, $guards);
    }
}
