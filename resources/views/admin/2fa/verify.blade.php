<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Two-Factor Authentication - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Figtree', sans-serif;
        }
        
        .verification-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            max-width: 450px;
            width: 100%;
            text-align: center;
        }
        
        .verification-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2rem;
        }
        
        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 1rem;
            font-size: 1.1rem;
            text-align: center;
            letter-spacing: 0.5rem;
            font-weight: 600;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        
        .btn-outline-secondary {
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
        }
        
        .demo-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        
        .demo-code {
            background: #e9ecef;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-family: monospace;
            font-weight: bold;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="verification-container">
                    <div class="verification-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    
                    <h2 class="mb-3">Two-Factor Authentication</h2>
                    <p class="text-muted mb-4">
                        Please enter the 6-digit verification code from your authenticator app to continue.
                    </p>

                    @if (session('info'))
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ session('info') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.2fa.verify') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="code" class="form-label fw-bold">Verification Code</label>
                            <input type="text" 
                                   name="code" 
                                   id="code" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   placeholder="000000" 
                                   maxlength="6" 
                                   pattern="[0-9]{6}" 
                                   inputmode="numeric"
                                   autocomplete="one-time-code"
                                   required 
                                   autofocus>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>
                                Verify Code
                            </button>
                            
                            <a href="{{ route('auth.login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>
                                Back to Login
                            </a>
                        </div>
                    </form>

                    <!-- Demo Information -->
                    <div class="demo-info">
                        <h6 class="mb-2">
                            <i class="bi bi-info-circle me-2"></i>
                            Demo Mode
                        </h6>
                        <p class="mb-2">For testing purposes, use one of these codes:</p>
                        <div class="demo-code mb-2">123456</div>
                        <small class="text-muted">Or any 6-digit code starting with '1'</small>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-shield-exclamation me-1"></i>
                            Having trouble? Contact your system administrator.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-format the input to only allow numbers
        document.getElementById('code').addEventListener('input', function(e) {
            // Remove any non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 6 digits
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });

        // Auto-submit when 6 digits are entered
        document.getElementById('code').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                // Small delay for better UX
                setTimeout(() => {
                    document.querySelector('form').submit();
                }, 500);
            }
        });

        // Handle paste events
        document.getElementById('code').addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text');
            const numericData = pastedData.replace(/[^0-9]/g, '').slice(0, 6);
            this.value = numericData;
            
            // Auto-submit if 6 digits
            if (numericData.length === 6) {
                setTimeout(() => {
                    document.querySelector('form').submit();
                }, 100);
            }
        });

        // Focus on input when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('code').focus();
        });
    </script>
</body>
</html>
