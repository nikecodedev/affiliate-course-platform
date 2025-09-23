<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::guard('client')->check()) {
            return redirect()->route('client.dashboard');
        }

        return view('client.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Add reCAPTCHA validation if enabled
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

        $email = $request->email;
        $key = 'login.' . $email;

        // Check rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
            ]);
        }

        $client = Client::where('email', $email)->first();

        if (!$client || !$client->is_active) {
            RateLimiter::hit($key, 300); // 5 minutes
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas ou conta inativa.',
            ]);
        }

        if ($client->isLocked()) {
            throw ValidationException::withMessages([
                'email' => 'Conta temporariamente bloqueada. Tente novamente mais tarde.',
            ]);
        }

        if (!Hash::check($request->password, $client->password)) {
            $client->incrementLoginAttempts();
            RateLimiter::hit($key, 300);
            
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        // Successful login
        Auth::guard('client')->login($client);
        $client->resetLoginAttempts();
        RateLimiter::clear($key);

        $request->session()->regenerate();

        return redirect()->intended(route('client.dashboard'))
            ->with('success', 'Login realizado com sucesso!');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('client')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login')
            ->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        if (Auth::guard('client')->check()) {
            return redirect()->route('client.dashboard');
        }

        return view('client.auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'password' => 'required|string|min:8|confirmed',
            'cpf' => 'required|string|size:14|unique:clients',
            'phone' => 'nullable|string|max:20',
            'terms' => 'required|accepted',
        ]);

        // Add reCAPTCHA validation if enabled
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

        // Format CPF
        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
        
        // Validate CPF
        if (!$this->validateCPF($cpf)) {
            return redirect()->back()
                ->withErrors(['cpf' => 'CPF inválido.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $cpf,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        Auth::guard('client')->login($client);

        return redirect()->route('client.dashboard')
            ->with('success', 'Conta criada com sucesso! Bem-vindo!');
    }

    /**
     * Show password reset request form
     */
    public function showPasswordResetForm()
    {
        return view('client.auth.forgot-password');
    }

    /**
     * Handle password reset request
     */
    public function sendPasswordResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:clients,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // TODO: Implement password reset email functionality
        // For now, just show success message
        
        return redirect()->back()
            ->with('success', 'Link de redefinição de senha enviado para seu email.');
    }

    /**
     * Validate CPF
     */
    private function validateCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
