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
    public function boot(): void
    {
        // Custom reCAPTCHA validation rule
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            if (!config('recaptcha.enabled')) {
                return true;
            }

            $secretKey = config('recaptcha.secret_key');
            if (!$secretKey) {
                return false;
            }

            $response = file_get_contents(
                'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $value . '&remoteip=' . request()->ip()
            );

            $responseData = json_decode($response, true);
            return $responseData['success'] ?? false;
        });

        Validator::replacer('recaptcha', function ($message, $attribute, $rule, $parameters) {
            return 'reCAPTCHA verification failed. Please try again.';
        });
    }
}

