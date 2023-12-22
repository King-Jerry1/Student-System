<?php

namespace App\Providers;

use App\Models\Students;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::share('title', 'Student Login ');

        // View::composer('students.index', function($view){
        //     $view ->with('students', Students::all());
        // });

    }
}