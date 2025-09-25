<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\CampaignController;
use App\Http\Controllers\Web\PilgrimController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\DocumentController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\SystemSettingController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth', 'role'])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Campaign management
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/', [CampaignController::class, 'store'])->name('store');
        Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
        Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
        Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
        Route::post('/{campaign}/activate', [CampaignController::class, 'activate'])->name('activate');
        Route::post('/{campaign}/deactivate', [CampaignController::class, 'deactivate'])->name('deactivate');
    });

    // Pilgrim management
    Route::prefix('pilgrims')->name('pilgrims.')->group(function () {
        Route::get('/', [PilgrimController::class, 'index'])->name('index');
        Route::get('/create', [PilgrimController::class, 'create'])->name('create');
        Route::post('/', [PilgrimController::class, 'store'])->name('store');
        Route::get('/{pilgrim}', [PilgrimController::class, 'show'])->name('show');
        Route::get('/{pilgrim}/edit', [PilgrimController::class, 'edit'])->name('edit');
        Route::put('/{pilgrim}', [PilgrimController::class, 'update'])->name('update');
        Route::delete('/{pilgrim}', [PilgrimController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [PilgrimController::class, 'exportExcel'])->name('exportExcel');
        Route::post('/import/excel', [PilgrimController::class, 'importExcel'])->name('importExcel');
    });

    // Payment management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::get('/{payment}/receipt', [PaymentController::class, 'generateReceipt'])->name('receipt');
    });

    // Document management (pilgrim-specific)
    Route::prefix('pilgrims/{pilgrim}/documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::put('/', [DocumentController::class, 'update'])->name('update');
        Route::get('/checklist', [DocumentController::class, 'checklist'])->name('checklist');
        Route::get('/download/{type}', [DocumentController::class, 'download'])->name('download');
        Route::delete('/delete/{type}', [DocumentController::class, 'deleteFile'])->name('delete-file');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/campaigns', [ReportController::class, 'campaigns'])->name('campaigns');
        Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
        Route::get('/pilgrims', [ReportController::class, 'pilgrims'])->name('pilgrims');
        Route::get('/documents', [ReportController::class, 'documents'])->name('documents');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });

    // User management (admin only)
    Route::prefix('users')->name('users.')->middleware('role:manage-users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // System settings (admin only)
    Route::prefix('settings')->name('settings.')->middleware('role:manage-settings')->group(function () {
        Route::get('/', [SystemSettingController::class, 'index'])->name('index');
        Route::get('/{category}', [SystemSettingController::class, 'show'])->name('show');
        Route::put('/{category}', [SystemSettingController::class, 'update'])->name('update');
    });

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('profile.show');
        })->name('show');
        Route::get('/edit', function () {
            return view('profile.edit');
        })->name('edit');
        Route::put('/', function () {
            // Handle profile update
            return redirect()->route('profile.show');
        })->name('update');
    });
});
