<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;

class AppServiceProvider extends ServiceProvider 
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('ifsc', function($attribute, $value, $parameters)
        {
            $rst=ifsccodeCheck($value);
            if($rst['code']==0 && $rst['error']==false)
                return true;
            return false;
        });
    }
}
