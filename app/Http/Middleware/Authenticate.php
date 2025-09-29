<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // Clear any old logout message (if needed)
            session()->forget('message');
            session()->flash('message', 'Please log in first.');
            session()->flash('type', 'warning');
            return route('login');
        }

        return null;
    }
}
