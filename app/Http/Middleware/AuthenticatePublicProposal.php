<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatePublicProposal extends Authenticate
{
    /**
     * Handle an incoming request.
     */
    protected function authenticate($request, array $guards): void
    {
        // Allow sign-proposal route to be accessed without authentication
        if ($request->routeIs('filament.admin.resources.proposals.sign-proposal') || 
            str_contains($request->path(), 'sign-proposal')) {
            return;
        }

        parent::authenticate($request, $guards);
    }
}
