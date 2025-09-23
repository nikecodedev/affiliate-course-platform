<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;
use App\Models\Admin;
use App\Models\SystemSetting;

class AuthController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Only validate reCAPTCHA if it's enabled and keys are configured
        if (config('recaptcha.enabled') && config('recaptcha.site_key') && config('recaptcha.secret_key')) {
            $validator->addRules([
                'g-recaptcha-response' => 'required|recaptcha',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            
            // Check if 2FA is enabled
            if ($admin->two_factor_enabled) {
                return redirect()->route('admin.2fa.verify');
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid credentials'])
            ->withInput($request->except('password'));
    }

    /**
     * Show 2FA verification form
     */
    public function show2FAForm()
    {
        return view('admin.auth.2fa');
    }

    /**
     * Verify 2FA code
     */
    public function verify2FA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $admin = Auth::guard('admin')->user();
        $secret = $admin->two_factor_secret;

        if ($this->google2fa->verifyKey($secret, $request->code)) {
            session(['admin_2fa_verified' => true]);
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()
            ->withErrors(['code' => 'Invalid 2FA code']);
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

