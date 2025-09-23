<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FinancialController;

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

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/2fa', [AuthController::class, 'show2FAForm'])->name('2fa.verify');
        Route::post('/2fa', [AuthController::class, 'verify2FA']);
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['auth:admin', 'admin.2fa'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
        Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('chart.data');

        // System Settings
        Route::get('/settings', [SystemSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SystemSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/upload-logo', [SystemSettingsController::class, 'uploadLogo'])->name('settings.upload.logo');
        Route::post('/settings/upload-background', [SystemSettingsController::class, 'uploadBackground'])->name('settings.upload.background');
        Route::post('/settings/tracking', [SystemSettingsController::class, 'updateTracking'])->name('settings.tracking');
        Route::post('/settings/recaptcha', [SystemSettingsController::class, 'updateRecaptcha'])->name('settings.recaptcha');
        Route::post('/settings/financial', [SystemSettingsController::class, 'updateFinancial'])->name('settings.financial');

        // Plans Management
        Route::resource('plans', PlanController::class);
        Route::patch('/plans/{plan}/toggle-status', [PlanController::class, 'toggleStatus'])->name('plans.toggle.status');

        // Courses Management
        Route::resource('courses', CourseController::class);
        Route::resource('courses.modules', CourseController::class)->except(['index', 'show']);
        Route::resource('courses.modules.lessons', CourseController::class)->except(['index', 'show']);

        // Sales Management
        Route::resource('sales', SaleController::class);
        Route::patch('/sales/{sale}/confirm', [SaleController::class, 'confirm'])->name('sales.confirm');
        Route::patch('/sales/{sale}/refund', [SaleController::class, 'refund'])->name('sales.refund');

        // Users Management
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-affiliate', [UserController::class, 'toggleAffiliate'])->name('users.toggle.affiliate');

        // Financial Management
        Route::prefix('financial')->name('financial.')->group(function () {
            Route::get('/', [FinancialController::class, 'index'])->name('index');
            Route::get('/reports', [FinancialController::class, 'reports'])->name('reports');
            Route::get('/withdrawals', [FinancialController::class, 'withdrawals'])->name('withdrawals');
            Route::patch('/withdrawals/{withdrawal}/process', [FinancialController::class, 'processWithdrawal'])->name('withdrawals.process');
            Route::resource('expenses', FinancialController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
            Route::resource('commission-payments', FinancialController::class)->only(['index', 'create', 'store', 'show']);
        });
    });
});

