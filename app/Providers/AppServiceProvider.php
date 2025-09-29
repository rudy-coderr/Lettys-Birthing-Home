<?php
namespace App\Providers;

use App\Models\Emergency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Instead of '*', gawin mo lang specific sa layout kung saan kailangan
        View::composer('partials.emergencyModal', function ($view) {
            if (Auth::check() && Auth::user()->role === 'staff') {
                $emergencies = Emergency::with('branch')
                    ->where('branch_id', Auth::user()->staff->branch_id ?? null)
                    ->latest()
                    ->get();
            } elseif (Auth::check() && Auth::user()->role === 'admin') {
                $emergencies = Emergency::with('branch')
                    ->latest()
                    ->get();
            } else {
                $emergencies = collect();
            }

            $view->with('emergencies', $emergencies);
        });

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $view->with('unreadNotifications', $user->unreadNotifications);
            } else {
                $view->with('unreadNotifications', collect());
            }
        });
    }
}
