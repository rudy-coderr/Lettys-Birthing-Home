<?php
namespace App\Http\Controllers;

use App\Mail\TwoFactorCodeMail;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('swal', [
                'icon'  => 'error',
                'title' => 'Session Expired',
                'text'  => 'Please login again.',
            ]);
        }

        if ($user->two_factor_code === null) {
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'staff' => redirect()->route('staff.dashboard'),
                default => redirect('/'),
            };
        }

        if (strtotime('now') >= strtotime($user->two_factor_expires_at)) {
            // ✅ log expired code attempt
            AuditLog::create([
                'staff_id'   => $user->staff->id ?? null,
                'action'     => '2FA Expired',
                'details'    => "2FA code expired for {$user->full_name}",
                'created_at' => now(),
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('swal', [
                'icon'  => 'error',
                'title' => 'Verification Code Expired',
                'text'  => 'Please login again.',
            ]);
        }

        return view('auth.twofactor');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required',
        ]);

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('swal', [
                'icon'  => 'error',
                'title' => 'Session Expired',
                'text'  => 'Please login again.',
            ]);
        }

        if (
            $request->input('two_factor_code') === $user->two_factor_code &&
            strtotime('now') < strtotime($user->two_factor_expires_at)
        ) {
            $user->resetTwoFactorCode();

            $fullName = $user->full_name;

            // ✅ log success
            AuditLog::create([
                'staff_id'   => $user->staff->id ?? null,
                'action'     => '2FA Verified',
                'details'    => "Successful login for {$fullName}",
                'created_at' => now(),
            ]);

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('swal', [
                    'icon'  => 'success',
                    'title' => 'Login Successful',
                    'text'  => "Welcome, {$fullName}!",
                ]),
                'staff' => redirect()->route('staff.dashboard')->with('swal', [
                    'icon'  => 'success',
                    'title' => 'Login Successful',
                    'text'  => "Welcome, {$fullName}!",
                ]),
                default => redirect('/')->with('swal', [
                    'icon'  => 'success',
                    'title' => 'Two-Factor Successful',
                    'text'  => "Welcome, {$fullName}!",
                ]),
            };
        }

        // ✅ log failed attempt
        AuditLog::create([
            'staff_id'   => $user->staff->id ?? null,
            'action'     => '2FA Failed',
            'details'    => "Invalid 2FA attempt for {$user->full_name}",
            'created_at' => now(),
        ]);

        return back()->with('swal', [
            'icon'  => 'error',
            'title' => 'Invalid Code',
            'text'  => 'The two-factor code is invalid or expired.',
        ]);
    }

    public function resend(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('swal', [
                'icon'  => 'error',
                'title' => 'Session Expired',
                'text'  => 'Please login again.',
            ]);
        }

        $user->generateTwoFactorCode();
        Mail::to($user->email)->send(new TwoFactorCodeMail($user));

        // ✅ log resend
        AuditLog::create([
            'staff_id'   => $user->staff->id ?? null,
            'action'     => '2FA Code Resent',
            'details'    => "New 2FA code sent to {$user->email}",
            'created_at' => now(),
        ]);

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Verification Code Sent!',
            'text'  => 'A new verification code has been sent to your email.',
        ]);
    }
}
