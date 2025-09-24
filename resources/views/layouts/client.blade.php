<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client Area') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5dade2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5dade2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        }
        
        .progress {
            height: 8px;
            border-radius: 10px;
            background-color: rgba(0,0,0,0.1);
        }
        
        .progress-bar {
            border-radius: 10px;
            background: linear-gradient(90deg, var(--success-color) 0%, #2ecc71 100%);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-left: 4px solid var(--secondary-color);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5dade2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--light-color);
            border: none;
            font-weight: 600;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 20px 0;
            margin-top: 40px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .main-content {
                padding: 10px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <!-- Logo/Brand -->
                    <div class="text-center mb-4">
                        <h4 class="navbar-brand mb-0">
                            <i class="bi bi-person-circle me-2"></i>
                            Client Area
                        </h4>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}" 
                               href="{{ route('client.dashboard') }}">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.profile.*') ? 'active' : '' }}" 
                               href="{{ route('client.profile.index') }}">
                                <i class="bi bi-person"></i>
                                My Profile
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.leads.*') ? 'active' : '' }}" 
                               href="{{ route('client.leads.index') }}">
                                <i class="bi bi-people"></i>
                                Leads
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.financial.*') ? 'active' : '' }}" 
                               href="{{ route('client.financial.index') }}">
                                <i class="bi bi-cash-stack"></i>
                                Financial
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.bonus.*') ? 'active' : '' }}" 
                               href="{{ route('client.bonus.index') }}">
                                <i class="bi bi-gift"></i>
                                Bonuses
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.network.*') ? 'active' : '' }}" 
                               href="{{ route('client.network.index') }}">
                                <i class="bi bi-diagram-3"></i>
                                Network
                            </a>
                        </li>
                        
                        @if(auth('client')->user() && auth('client')->user()->hasCourseAccess())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.training.*') ? 'active' : '' }}" 
                               href="{{ route('client.training.index') }}">
                                <i class="bi bi-book"></i>
                                Training
                            </a>
                        </li>
                        @endif
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.support.*') ? 'active' : '' }}" 
                               href="{{ route('client.support.index') }}">
                                <i class="bi bi-headset"></i>
                                Support
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.capture-sites') ? 'active' : '' }}" 
                               href="{{ route('client.capture-sites') }}">
                                <i class="bi bi-link-45deg"></i>
                                Capture Sites
                            </a>
                        </li>
                    </ul>
                    
                    <!-- User Info -->
                    @auth('client')
                    <div class="mt-5 pt-3 border-top">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">
                                {{ substr(auth('client')->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-white fw-bold">{{ auth('client')->user()->name }}</div>
                                <small class="text-white-50">{{ auth('client')->user()->masked_email }}</small>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Top Navigation -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @hasSection('page-actions')
                            @yield('page-actions')
                        @endif
                        
                        <!-- Notifications -->
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-bell"></i>
                                <span class="badge bg-danger">3</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><h6 class="dropdown-header">Notificações</h6></li>
                                <li><a class="dropdown-item" href="#">Nova mensagem de suporte</a></li>
                                <li><a class="dropdown-item" href="#">Lead convertido</a></li>
                                <li><a class="dropdown-item" href="#">Pagamento aprovado</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Ver todas</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">
                        <a href="{{ route('client.support.faq') }}" class="text-white me-3">FAQ</a>
                        <a href="{{ route('client.support.create') }}" class="text-white">Support</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Add loading states to forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processando...';
                    }
                });
            });
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
