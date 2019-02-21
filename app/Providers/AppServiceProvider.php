<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });
        Validator::extend('float2left', function($attribute, $value)
        {
            return preg_match('/^[0-9]?[0-9]?(\.[0-9][0-9]?)?$/', $value);
        });
        Validator::extend('float5left', function($attribute, $value)
        {
            return preg_match('/^[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?(\.[0-9][0-9]?)?$/', $value);
        });
        Validator::extend('float9left', function($attribute, $value)
        {
            return preg_match('/^[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?(\.[0-9][0-9]?)?$/', $value);
        });
        Validator::extend('float7left', function($attribute, $value)
        {
            return preg_match('/^[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?[0-9]?(\.[0-9][0-9]?)?$/', $value);
        });
        Validator::extend('alpha_specialchars', function($attribute, $value)
        {
            return preg_match('/(^[ A-Za-z0-9 \,\&\.\_\-\/]+$)+/', $value);
        });
        Validator::extend('zip', function($attribute, $value)
        {
            return preg_match('/^[0-9]{6}(\-[0-9]{4})?$/', $value);
        });
        Validator::extend('mobile', function($attribute, $value)
        {
            return preg_match('/^[0-9]{10}$/', $value);
        });
        Validator::extend('ifsc', function($attribute, $value)
        {
            return preg_match('/^[A-Za-z0-9]{11}$/', $value);
        });
        Validator::extend('alpha_space_num', function($attribute, $value)
        {
            return preg_match('/^[a-zA-Z0-9]+([a-zA-Z0-9\s.\/\(\)\%\+\-])*$/', $value);
        });
        Validator::extend('alpha_space_sym', function($attribute, $value)
        {
            return preg_match('/^[a-zA-Z0-9]+([ a-zA-Z0-9\@\.\/\#\,\&\-\'\%\:\(\)\+])*$/', $value);
        });
        Validator::extend('alpha_space_num_specialchar', function($attribute, $value)
        {
            return preg_match('/(^[A-Za-z0-9 ,&._-]+$)+/', $value);
        });
        Validator::extend('alpha_space_num_special', function($attribute, $value)
        {
            return preg_match('/^[a-zA-Z0-9]+([ a-zA-Z0-9\_\.\,\&\-\(\)\+\%\/])*$/', $value);
        });
        Validator::extend('alpha_num', function($attribute, $value)
        {
            return preg_match('/^[a-zA-Z0-9]+$/', $value);
        });
        Validator::extend('alpha_num_hyphen', function($attribute, $value)
        {
            return preg_match('/^[a-zA-Z0-9-]+$/', $value);
        });
        Validator::extend('pan', function($attribute, $value)
        {
            return preg_match('/^[a-zA-Z]{5}[0-9]{4}[A-Za-z]{1}$/', $value);
        });
        Validator::extend('chk_email', function($attribute, $value)
        {
            return preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $value);
        });
        Validator::extend('tan', function($attribute, $value)
        {
            return preg_match('/^[a-zA-Z]{4}[0-9]{5}[A-Za-z]{1}$/', $value);
        });
        Validator::extend('vat', function($attribute, $value)
        {
            return preg_match('/^[0-9]{11}[Vv]{1}$/', $value);
        });
        Validator::extend('cst', function($attribute, $value)
        {
            return preg_match('/^[0-9]{11}[Cc]{1}$/', $value);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
