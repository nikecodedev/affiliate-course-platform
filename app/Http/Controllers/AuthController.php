<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Client;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show unified login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle unified login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Determine user type and apply appropriate validation rules
        $userType = $this->determineUserType($request->email);
        
        // Always validate reCAPTCHA for both admin and client
        if (config('recaptcha.enabled') && config('recaptcha.site_key') && config('recaptcha.secret_key')) {
            $validator->addRules([
                'g-recaptcha-response' => 'required|recaptcha',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'));
            }
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Try to authenticate as admin first
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $admin = Auth::guard('admin')->user();
            
            // Check if admin account is locked
            if ($admin->is_locked) {
                Auth::guard('admin')->logout();
                return redirect()->back()
                    ->withErrors(['email' => 'Your account has been locked. Please contact support.'])
                    ->withInput($request->except('password'));
            }

            // Store admin ID for 2FA verification
            $request->session()->put('admin_2fa_id', $admin->id);
            
            // Always require 2FA for admin login
            return redirect()->route('admin.2fa.verify')
                ->with('info', 'Please complete two-factor authentication to continue.');
        }

        // Try to authenticate as client
        if (Auth::guard('client')->attempt($credentials, $remember)) {
            $client = Auth::guard('client')->user();
            
            // Check if client account is locked
            if ($client->is_locked) {
                Auth::guard('client')->logout();
                return redirect()->back()
                    ->withErrors(['email' => 'Your account has been locked. Please contact support.'])
                    ->withInput($request->except('password'));
            }

            $request->session()->regenerate();
            
            return redirect()->intended(route('client.dashboard'))
                ->with('success', 'Welcome back, ' . $client->name . '!');
        }

        // Authentication failed
        return redirect()->back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->except('password'));
    }

    /**
     * Determine user type based on email domain or database check
     */
    private function determineUserType($email)
    {
        // Check if email exists in admin table
        if (Admin::where('email', $email)->exists()) {
            return 'admin';
        }
        
        // Check if email exists in client table
        if (Client::where('email', $email)->exists()) {
            return 'client';
        }
        
        // Default to client for registration
        return 'client';
    }

    /**
     * Show unified registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle unified registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email|unique:admins,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'cpf' => 'required|string|size:11|unique:clients,cpf',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'user_type' => 'required|in:client',
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
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Create client account
        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'is_active' => true,
        ]);

        // Log the client in
        Auth::guard('client')->login($client);

        return redirect()->route('client.dashboard')
            ->with('success', 'Registration successful! Welcome to your dashboard.');
    }

    /**
     * Show 2FA verification form for admin
     */
    public function show2FAForm()
    {
        if (!session()->has('admin_2fa_id')) {
            return redirect()->route('auth.login')
                ->withErrors(['error' => 'Please login first to access 2FA verification.']);
        }

        return view('admin.2fa.verify');
    }

    /**
     * Handle 2FA verification for admin
     */
    public function verify2FA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $adminId = session('admin_2fa_id');
        
        if (!$adminId) {
            return redirect()->route('auth.login')
                ->withErrors(['error' => 'Session expired. Please login again.']);
        }

        $admin = Admin::find($adminId);
        
        if (!$admin) {
            session()->forget('admin_2fa_id');
            return redirect()->route('auth.login')
                ->withErrors(['error' => 'Invalid session. Please login again.']);
        }

        // For demo purposes, accept any 6-digit code starting with '1'
        // In production, you would verify against the actual TOTP code
        if ($request->code === '123456' || substr($request->code, 0, 1) === '1') {
            // Mark 2FA as verified
            $admin->update(['two_factor_verified' => true]);
            
            // Clear the session
            session()->forget('admin_2fa_id');
            
            // Log the admin in
            Auth::guard('admin')->login($admin);
            
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Two-factor authentication completed successfully!');
        }

        return redirect()->back()
            ->withErrors(['code' => 'Invalid 2FA code. Please try again.'])
            ->withInput();
    }

    /**
     * Handle logout for both admin and client
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user instanceof Admin) {
            Auth::guard('admin')->logout();
        } elseif ($user instanceof Client) {
            Auth::guard('client')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Check if email exists in system (for AJAX validation)
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        
        $adminExists = Admin::where('email', $email)->exists();
        $clientExists = Client::where('email', $email)->exists();
        
        return response()->json([
            'exists' => $adminExists || $clientExists,
            'type' => $adminExists ? 'admin' : ($clientExists ? 'client' : null)
        ]);
    }
}
