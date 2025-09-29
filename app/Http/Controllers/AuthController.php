<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function auth()
    {
        return view('auth.login');
    }

    public function process(Request $request)
    {
        // Validate including captcha with custom message
        $request->validate([
            'email'                => ['required', 'email'],
            'password'             => ['required'],
            'g-recaptcha-response' => ['required', 'captcha'],
        ], [
            'g-recaptcha-response.required' => "Oops! Please verify you’re not a robot.",
            'g-recaptcha-response.captcha'  => "Oops! Please verify you’re not a robot.",
        ]);

        // Credentials lang (hindi kasama captcha)
        $credentials = $request->only('email', 'password');

        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Too Many Attempts',
                'text'  => "Please try again after " . ceil($seconds / 60) . " minutes.",
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && ! $user->is_active) {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Account Deactivated',
                'text'  => 'Your account has been deactivated. Please contact the administrator.',
            ]);
        }

        if (Auth::attempt(array_merge($credentials, ['is_active' => 1]))) {
            RateLimiter::clear($key);

            $user = Auth::user();
            $user->generateTwoFactorCode();
            Mail::to($user->email)->send(new \App\Mail\TwoFactorCodeMail($user));

            return redirect()->route('2fa.verify')->with('swal', [
                'icon'  => 'success',
                'title' => 'Verification Code Sent!',
                'text'  => 'A verification code has been sent to your email.',
            ]);
        }

        RateLimiter::hit($key, 300);

        return back()->with('swal', [
            'icon'  => 'error',
            'title' => 'Login Failed',
            'text'  => 'Invalid email or password. Please try again.',
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            \App\Models\AuditLog::create([
                'staff_id'   => optional($user->staff)->id,
                'action'     => 'Logout',
                'details'    => "{$user->full_name} logged out successfully",
                'created_at' => now(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('swal', [
            'icon'  => 'info',
            'title' => 'Logged Out!',
            'text'  => 'You have been logged out successfully.',
        ]);
    }
}
