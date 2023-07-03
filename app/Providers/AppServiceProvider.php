<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot():void
    {
        Validator::extend('ucwords_transform', function ($attribute, $value, $parameters, $validator) {
            $transformedValue = ucwords($value);
            $validator->setData([$attribute => $transformedValue]);
    
            return true;
        });
        Validator::extend('ucfirst_transform', function ($attribute, $value, $parameters, $validator) {
            $transformedValue = ucfirst($value);
            $validator->setData([$attribute => $transformedValue]);
    
            return true;
        });
    }


}