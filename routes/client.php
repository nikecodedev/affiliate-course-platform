<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\LeadController;
use App\Http\Controllers\Client\FinancialController;
use App\Http\Controllers\Client\TrainingController;
use App\Http\Controllers\Client\SupportController;

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
|
| Here is where you can register client routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "client" middleware group.
|
*/

// Client Authentication Routes
Route::prefix('client')->name('client.')->group(function () {
    
    // Public routes (not authenticated)
    Route::middleware('guest:client')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/forgot-password', [AuthController::class, 'showPasswordResetForm'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
    });

    // Protected routes (authenticated clients only)
    Route::middleware(['auth:client', 'client'])->group(function () {
        
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/capture-sites', [DashboardController::class, 'captureSites'])->name('capture-sites');
        Route::get('/capture-site/{type}', [DashboardController::class, 'showCaptureSite'])->name('capture-site');
        Route::get('/quick-stats', [DashboardController::class, 'getQuickStats'])->name('quick-stats');
        Route::get('/recent-activity', [DashboardController::class, 'getRecentActivity'])->name('recent-activity');
        
        // Profile Management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::get('/tracking-tags', [ProfileController::class, 'trackingTags'])->name('tracking-tags');
            Route::put('/tracking-tags', [ProfileController::class, 'updateTrackingTags'])->name('update-tracking-tags');
            Route::get('/security', [ProfileController::class, 'security'])->name('security');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('update-password');
            Route::get('/tracking-code', [ProfileController::class, 'generateTrackingCode'])->name('generate-tracking-code');
            Route::get('/tracking-code/download', [ProfileController::class, 'downloadTrackingCode'])->name('download-tracking-code');
            Route::get('/account-stats', [ProfileController::class, 'getAccountStats'])->name('account-stats');
        });
        
        // Lead Management
        Route::prefix('leads')->name('leads.')->group(function () {
            Route::get('/', [LeadController::class, 'index'])->name('index');
            Route::get('/create', [LeadController::class, 'create'])->name('create');
            Route::post('/', [LeadController::class, 'store'])->name('store');
            Route::get('/{lead}', [LeadController::class, 'show'])->name('show');
            Route::get('/{lead}/edit', [LeadController::class, 'edit'])->name('edit');
            Route::put('/{lead}', [LeadController::class, 'update'])->name('update');
            Route::delete('/{lead}', [LeadController::class, 'destroy'])->name('destroy');
            Route::post('/{lead}/contact', [LeadController::class, 'markAsContacted'])->name('contact');
            Route::post('/{lead}/convert', [LeadController::class, 'markAsConverted'])->name('convert');
            Route::post('/import', [LeadController::class, 'import'])->name('import');
            Route::get('/export', [LeadController::class, 'export'])->name('export');
        });
        
        // Financial Module
        Route::prefix('financial')->name('financial.')->group(function () {
            Route::get('/', [FinancialController::class, 'index'])->name('index');
            Route::get('/transactions', [FinancialController::class, 'transactions'])->name('transactions');
            Route::get('/invoices', [FinancialController::class, 'invoices'])->name('invoices');
            Route::get('/invoices/{invoice}', [FinancialController::class, 'showInvoice'])->name('invoice.show');
            Route::post('/invoices/{invoice}/receipt', [FinancialController::class, 'uploadReceipt'])->name('invoice.upload-receipt');
            Route::get('/withdrawals', [FinancialController::class, 'withdrawals'])->name('withdrawals');
            Route::get('/withdrawals/create', [FinancialController::class, 'createWithdrawal'])->name('withdrawals.create');
            Route::post('/withdrawals', [FinancialController::class, 'storeWithdrawal'])->name('withdrawals.store');
            Route::get('/withdrawals/{withdrawal}', [FinancialController::class, 'showWithdrawal'])->name('withdrawals.show');
            Route::get('/statements', [FinancialController::class, 'statements'])->name('statements');
            Route::get('/statements/download', [FinancialController::class, 'downloadStatement'])->name('statements.download');
        });
        
        // Training Area (only if client has course access)
        Route::prefix('training')->name('training.')->group(function () {
            Route::get('/', [TrainingController::class, 'index'])->name('index');
            Route::get('/courses', [TrainingController::class, 'courses'])->name('courses');
            Route::get('/courses/{course}', [TrainingController::class, 'showCourse'])->name('course.show');
            Route::get('/courses/{course}/modules/{module}', [TrainingController::class, 'showModule'])->name('module.show');
            Route::get('/courses/{course}/modules/{module}/lessons/{lesson}', [TrainingController::class, 'showLesson'])->name('lesson.show');
            Route::post('/courses/{course}/modules/{module}/lessons/{lesson}/progress', [TrainingController::class, 'updateProgress'])->name('lesson.progress');
            Route::post('/courses/{course}/modules/{module}/lessons/{lesson}/complete', [TrainingController::class, 'completeLesson'])->name('lesson.complete');
            Route::get('/progress', [TrainingController::class, 'progress'])->name('progress');
            Route::get('/certificates', [TrainingController::class, 'certificates'])->name('certificates');
            Route::get('/downloads', [TrainingController::class, 'downloads'])->name('downloads');
        });
        
        // Support System
        Route::prefix('support')->name('support.')->group(function () {
            Route::get('/', [SupportController::class, 'index'])->name('index');
            Route::get('/create', [SupportController::class, 'create'])->name('create');
            Route::post('/', [SupportController::class, 'store'])->name('store');
            Route::get('/tickets/{ticket}', [SupportController::class, 'show'])->name('ticket.show');
            Route::post('/tickets/{ticket}/responses', [SupportController::class, 'storeResponse'])->name('ticket.response');
            Route::post('/tickets/{ticket}/close', [SupportController::class, 'closeTicket'])->name('ticket.close');
            Route::get('/faq', [SupportController::class, 'faq'])->name('faq');
        });
        
        // Capture Sites (public routes with client code)
        Route::prefix('capture')->name('capture.')->group(function () {
            Route::get('/main/{code}', [DashboardController::class, 'showCaptureSite'])->name('main');
            Route::get('/secondary/{code}', [DashboardController::class, 'showCaptureSite'])->name('secondary');
        });
    });
});
