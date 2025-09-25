<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\PilgrimController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SystemSettingController;

Route::prefix('v1')->group(function () {

    // Routes publiques
    Route::post('/login', [AuthController::class, 'login']);

    // Paramètres publics
    Route::get('/settings/public', [SystemSettingController::class, 'getPublic']);

    // Routes protégées (authentifiées)
    Route::middleware(['auth:sanctum'])->group(function () {

        // === AUTHENTICATION ===
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);

        // === CAMPAIGNS ===
        Route::apiResource('/campaigns', CampaignController::class)->names([
            'index' => 'api.campaigns.index',
            'store' => 'api.campaigns.store',
            'show' => 'api.campaigns.show',
            'update' => 'api.campaigns.update',
            'destroy' => 'api.campaigns.destroy'
        ]);
        Route::put('/campaigns/{id}/activate', [CampaignController::class, 'activate'])->name('api.campaigns.activate');
        Route::get('/campaigns/{id}/statistics', [CampaignController::class, 'statistics'])->name('api.campaigns.statistics');

        // === PILGRIMS ===
        Route::apiResource('/pilgrims', PilgrimController::class)->names([
            'index' => 'api.pilgrims.index',
            'store' => 'api.pilgrims.store',
            'show' => 'api.pilgrims.show',
            'update' => 'api.pilgrims.update',
            'destroy' => 'api.pilgrims.destroy'
        ]);
        Route::put('/pilgrims/{id}/status', [PilgrimController::class, 'updateStatus'])->name('api.pilgrims.updateStatus');
        Route::get('/pilgrims/search/query', [PilgrimController::class, 'search'])->name('api.pilgrims.search');
        Route::get('/pilgrims/export/data', [PilgrimController::class, 'export'])->name('api.pilgrims.export');

        // === PAYMENTS ===
        Route::apiResource('/payments', PaymentController::class)->names([
            'index' => 'api.payments.index',
            'store' => 'api.payments.store',
            'show' => 'api.payments.show',
            'update' => 'api.payments.update',
            'destroy' => 'api.payments.destroy'
        ]);
        Route::get('/payments/{id}/receipt', [PaymentController::class, 'receipt'])->name('api.payments.receipt');
        Route::get('/payments/pilgrim/{pilgrimId}', [PaymentController::class, 'pilgrims'])->name('api.payments.pilgrims');
        Route::get('/payments/stats/overview', [PaymentController::class, 'statistics'])->name('api.payments.statistics');

        // === DOCUMENTS ===
        Route::get('/documents/pilgrim/{pilgrimId}', [DocumentController::class, 'show'])->name('api.documents.show');
        Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('api.documents.upload');
        Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('api.documents.download');
        Route::delete('/documents/{id}/delete', [DocumentController::class, 'delete'])->name('api.documents.delete');
        Route::get('/documents/pilgrim/{pilgrimId}/completeness', [DocumentController::class, 'checkCompleteness'])->name('api.documents.checkCompleteness');
        Route::put('/documents/pilgrim/{pilgrimId}/status', [DocumentController::class, 'updateStatus'])->name('api.documents.updateStatus');

        // === REPORTS & STATISTICS ===
        Route::get('/reports/dashboard', [ReportController::class, 'dashboard'])->name('api.reports.dashboard');
        Route::get('/reports/campaigns', [ReportController::class, 'campaigns'])->name('api.reports.campaigns');
        Route::get('/reports/payments', [ReportController::class, 'payments'])->name('api.reports.payments');
        Route::get('/reports/pilgrims', [ReportController::class, 'pilgrims'])->name('api.reports.pilgrims');
        Route::get('/reports/documents', [ReportController::class, 'documents'])->name('api.reports.documents');
        Route::post('/reports/export', [ReportController::class, 'export'])->name('api.reports.export');

        // === USER MANAGEMENT ===
        Route::apiResource('/users', UserController::class)->names([
            'index' => 'api.users.index',
            'store' => 'api.users.store',
            'show' => 'api.users.show',
            'update' => 'api.users.update',
            'destroy' => 'api.users.destroy'
        ]);
        Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('api.users.resetPassword');
        Route::get('/users/roles/list', [UserController::class, 'getRoles'])->name('api.users.getRoles');
        Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->name('api.users.updateRole');
        Route::get('/users/{id}/activities', [UserController::class, 'activities'])->name('api.users.activities');

        // Profile management
        Route::get('/profile', [UserController::class, 'profile'])->name('api.profile.show');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('api.profile.update');

        // === SYSTEM SETTINGS ===
        Route::get('/settings', [SystemSettingController::class, 'index'])->name('api.settings.index');
        Route::get('/settings/{key}', [SystemSettingController::class, 'show'])->name('api.settings.show');
        Route::put('/settings', [SystemSettingController::class, 'update'])->name('api.settings.update');
        Route::get('/settings/categories/list', [SystemSettingController::class, 'categories'])->name('api.settings.categories');
        Route::put('/settings/bulk/{category}', [SystemSettingController::class, 'bulkUpdate'])->name('api.settings.bulkUpdate');
        Route::delete('/settings/cache', [SystemSettingController::class, 'cache'])->name('api.settings.cache');
        Route::post('/settings/reset', [SystemSettingController::class, 'reset'])->name('api.settings.reset');

    });

});