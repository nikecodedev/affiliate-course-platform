<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Affiliate Course Platform</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card welcome-card">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-rocket-takeoff text-primary" style="font-size: 4rem;"></i>
                        <h1 class="display-4 fw-bold text-primary mt-3">Affiliate Course Platform</h1>
                        <p class="lead text-muted">Professional affiliate and course management platform built with Laravel</p>
                        
                        <div class="row mt-5">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                                        <h5 class="mt-3">Secure Admin Panel</h5>
                                        <p class="text-muted">Two-factor authentication and reCAPTCHA protection</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                                        <h5 class="mt-3">Advanced Analytics</h5>
                                        <p class="text-muted">Comprehensive reports and financial tracking</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <i class="bi bi-book text-warning" style="font-size: 2rem;"></i>
                                        <h5 class="mt-3">EAD System</h5>
                                        <p class="text-muted">Unlimited courses with modules and lessons</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <i class="bi bi-people text-danger" style="font-size: 2rem;"></i>
                                        <h5 class="mt-3">Affiliate Network</h5>
                                        <p class="text-muted">Multi-level commission and bonus system</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <a href="/admin" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Access Admin Panel
                            </a>
                        </div>
                        
                        <div class="mt-4">
                            <small class="text-muted">
                                Default Admin Credentials:<br>
                                Email: admin@example.com | Password: password123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/welcome.blade.php ENDPATH**/ ?>