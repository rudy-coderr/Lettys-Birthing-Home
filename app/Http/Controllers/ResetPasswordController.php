<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'User Not Found',
                'text'  => 'No user found with that email address.',
            ]);
        }

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token'      => $token,
                'created_at' => date('Y-m-d H:i:s'),
            ]
        );

        $resetUrl = url('/reset-password') . '?token=' . $token . '&email=' . urlencode($request->email);

        Mail::to($user->email)->send(new ResetPasswordMail($resetUrl, $user));

        return back()->with('swal', [
            'icon'  => 'success',
            'title' => 'Reset Link Sent!',
            'text'  => 'We have emailed your password reset link.',
        ]);
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email|exists:users,email',
                'token'    => 'required',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
                ],
            ], [
                'password.regex'     => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
                'password.confirmed' => 'Password and confirm password do not match.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Invalid Password',
                'text'  => $e->validator->errors()->first('password'),
            ]);
        }

        $reset = DB::table('password_resets')->where([
            ['email', $request->email],
            ['token', $request->token],
        ])->first();

        if (! $reset) {
            return back()->with('swal', [
                'icon'  => 'error',
                'title' => 'Invalid Token',
                'text'  => 'The password reset link is invalid or expired.',
            ]);
        }

        // Update user password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/login')->with('swal', [
            'icon'  => 'success',
            'title' => 'Password Reset Successful',
            'text'  => 'You can now login with your new password.',
        ]);
    }

}
