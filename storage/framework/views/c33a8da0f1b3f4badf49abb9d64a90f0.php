<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(setting('seo_title', 'Affiliate & Course Platform')); ?></title>
    <meta name="description" content="<?php echo e(setting('seo_description', '')); ?>">
    <meta name="keywords" content="<?php echo e(setting('seo_keywords', '')); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(\App\Models\SystemCustomization::getFaviconUrl()); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: <?php echo e(\App\Models\SystemCustomization::getPrimaryColor()); ?>;
            --secondary-color: <?php echo e(\App\Models\SystemCustomization::getSecondaryColor()); ?>;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card .card-body {
            padding: 1.5rem;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="<?php echo e(\App\Models\SystemCustomization::getLogoUrl()); ?>" alt="Logo" style="max-height: 40px; margin-bottom: 10px;">
                        <h4 class="text-white"><?php echo e(\App\Models\SystemCustomization::getCompanyName()); ?></h4>
                        <small class="text-white-50">Admin Panel</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.settings*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.settings.index')); ?>">
                                <i class="bi bi-gear me-2"></i>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.plans*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.plans.index')); ?>">
                                <i class="bi bi-box me-2"></i>
                                Plans & Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.courses*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.courses.index')); ?>">
                                <i class="bi bi-book me-2"></i>
                                Courses (EAD)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.sales*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.sales.index')); ?>">
                                <i class="bi bi-cart-check me-2"></i>
                                Sales & Invoices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.users.index')); ?>">
                                <i class="bi bi-people me-2"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.financial*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.financial.index')); ?>">
                                <i class="bi bi-graph-up me-2"></i>
                                Financial
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.bonus*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.bonus.index')); ?>">
                                <i class="bi bi-gift me-2"></i>
                                Bonus Settings
                            </a>
                        </li>
                    </ul>

                    <div class="mt-5 pt-3 border-top border-white-50">
                        <div class="text-center">
                            <small class="text-white-50">Logged in as:</small><br>
                            <strong class="text-white"><?php echo e(auth('admin')->user()->name); ?></strong>
                        </div>
                        <div class="mt-3">
                            <form method="POST" action="<?php echo e(route('auth.logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <?php echo $__env->yieldContent('page-actions'); ?>
                    </div>
                </div>

                <!-- Alerts -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/layouts/admin.blade.php ENDPATH**/ ?>