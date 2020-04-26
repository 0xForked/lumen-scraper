<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     * @noinspection PhpIncludeInspection
     */
    public function register()
    {
        foreach (glob(app()->path() . '/Helpers/*.php') as $file) {
            require_once($file);
        }
    }
}
