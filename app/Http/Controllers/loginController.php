<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function auth()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        // No database yet, just redirect to dashboard
        return redirect()->route('dashboard');
    }
}
