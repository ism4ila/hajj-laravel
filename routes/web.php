<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Web\PilgrimController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\DocumentController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\SystemSettingController;
use App\Http\Controllers\Web\UserProfileController;
use App\Http\Controllers\Web\ClientController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
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

    // Export routes
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/campaigns/{campaign}/pilgrims', [ExportController::class, 'campaignPilgrims'])->name('campaign.pilgrims');
        Route::get('/campaigns/{campaign}/summary', [ExportController::class, 'campaignSummary'])->name('campaign.summary');
        Route::get('/pilgrims/{pilgrim}/payments', [ExportController::class, 'pilgrimPayments'])->name('pilgrim.payments');
        Route::get('/campaigns', [ExportController::class, 'allCampaigns'])->name('all.campaigns');
        Route::get('/clients', [ExportController::class, 'allClients'])->name('clients');
    });

    // Client management
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
        Route::match(['get', 'post'], '/search/ajax', [ClientController::class, 'search'])->name('search');
        Route::get('/departments/ajax', [ClientController::class, 'getDepartments'])->name('departments');
        Route::post('/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [ClientController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export', [ClientController::class, 'export'])->name('export');
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
        Route::get('/clients', [ReportController::class, 'clients'])->name('clients');
        Route::get('/payments', [ReportController::class, 'payments'])->name('payments');
        Route::get('/pilgrims', [ReportController::class, 'pilgrims'])->name('pilgrims');
        Route::get('/documents', [ReportController::class, 'documents'])->name('documents');
        Route::post('/export', [ReportController::class, 'export'])->name('export');
    });

    // System settings (admin seulement - simplifié)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SystemSettingController::class, 'index'])->name('index');
        Route::put('/', [SystemSettingController::class, 'update'])->name('update');
    });

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserProfileController::class, 'show'])->name('show');
        Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');
        Route::put('/', [UserProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [UserProfileController::class, 'showChangePasswordForm'])->name('change-password');
        Route::post('/change-password', [UserProfileController::class, 'changePassword']);
        Route::delete('/delete', [UserProfileController::class, 'destroy'])->name('destroy');
    });
});
