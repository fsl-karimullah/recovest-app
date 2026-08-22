<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BankConnectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VendorBillController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('bank-mutations')->name('bank-mutations.')->group(function () {
            Route::get('/', [BankConnectionController::class, 'index'])->name('index');
            Route::post('/{id}/sync', [BankConnectionController::class, 'sync'])->name('sync');
            Route::post('/{id}/simulate-webhook', [BankConnectionController::class, 'simulateWebhook'])->name('simulate-webhook');
        });

        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::post('/', [TransactionController::class, 'store'])->name('store');
            Route::put('/{id}', [TransactionController::class, 'update'])->name('update');
            Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('destroy');
            Route::post('/match', [TransactionController::class, 'matchMutation'])->name('match');
            Route::post('/from-mutation', [TransactionController::class, 'createFromMutation'])->name('create-from-mutation');
        });

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::post('/{id}/pay', [InvoiceController::class, 'markAsPaid'])->name('pay');
        });

        Route::prefix('bills')->name('bills.')->group(function () {
            Route::get('/', [VendorBillController::class, 'index'])->name('index');
            Route::post('/', [VendorBillController::class, 'store'])->name('store');
            Route::post('/{id}/pay', [VendorBillController::class, 'markAsPaid'])->name('pay');
        });

        Route::prefix('assets')->name('assets.')->group(function () {
            Route::get('/', [FixedAssetController::class, 'index'])->name('index');
            Route::post('/', [FixedAssetController::class, 'store'])->name('store');
        });

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});

require __DIR__.'/auth.php';
