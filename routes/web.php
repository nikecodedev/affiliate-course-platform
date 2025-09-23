<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\Admin2FAMiddleware;
use App\Http\Middleware\AuditMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Default route redirect to unified login
Route::get('/', function () {
    return redirect()->route('auth.login');
});

// Unified Authentication Routes
Route::name('auth.')->group(function () {
    // Guest routes (login, register)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
    });

    // Logout route (accessible by both admin and client)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Email check route (for AJAX validation)
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authenticated admin routes
    Route::middleware(['auth:admin', AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 2FA routes
        Route::middleware(Admin2FAMiddleware::class)->group(function () {
            Route::get('/2fa/verify', function () {
                return view('admin.2fa.verify');
            })->name('2fa.verify');
            Route::post('/2fa/verify', function (Request $request) {
                // Handle 2FA verification
                return redirect()->route('admin.dashboard');
            });
        });

        // System Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
            Route::post('/', [SystemSettingsController::class, 'update'])->name('update');
            Route::post('/upload-logo', [SystemSettingsController::class, 'uploadLogo'])->name('upload-logo');
        });

        // Plans and Products
        Route::resource('plans', PlanController::class);
        Route::resource('products', ProductController::class);

        // Sales Management
        Route::resource('sales', SaleController::class);
        Route::post('sales/{sale}/confirm', [SaleController::class, 'confirm'])->name('sales.confirm');
        Route::post('sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');

        // User Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::post('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');

        // Course Management
        Route::resource('courses', CourseController::class);
        Route::post('courses/{course}/activate', [CourseController::class, 'activate'])->name('courses.activate');
        Route::post('courses/{course}/deactivate', [CourseController::class, 'deactivate'])->name('courses.deactivate');

        // Financial Management
        Route::prefix('financial')->name('financial.')->group(function () {
            Route::get('/', [FinancialController::class, 'index'])->name('index');
            Route::get('/withdrawals', [FinancialController::class, 'withdrawals'])->name('withdrawals');
            Route::post('/withdrawals/{withdrawal}/approve', [FinancialController::class, 'approveWithdrawal'])->name('withdrawals.approve');
            Route::post('/withdrawals/{withdrawal}/reject', [FinancialController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
            Route::get('/reports', [FinancialController::class, 'reports'])->name('reports');
            Route::resource('expenses', FinancialController::class)->except(['index', 'show']);
            Route::get('/commission-payments', [FinancialController::class, 'commissionPaymentsIndex'])->name('commission-payments.index');
            Route::resource('commission-payments', FinancialController::class)->except(['index', 'show']);
        });

        // Audit Logs (with audit middleware)
        Route::middleware(AuditMiddleware::class)->group(function () {
            Route::get('/audit-logs', function () {
                return view('admin.audit-logs.index');
            })->name('audit-logs');
        });
    });
});