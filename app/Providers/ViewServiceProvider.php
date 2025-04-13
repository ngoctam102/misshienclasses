<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->hasRole('super_admin')) {
                $pendingCount = User::role('student')
                    ->where('is_approved', false)
                    ->where('is_online', true)
                    ->count();

                $view->with('pendingStudentsCount', $pendingCount);
            }
        });
    }
}
