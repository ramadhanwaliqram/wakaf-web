<?php

use App\Http\Controllers\admin\CommitteeController;
use App\Http\Controllers\admin\WakifController;
use App\Http\Controllers\admin\WakafController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\TransactionWakafController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the 'web' middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::namespace('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(
        function () {
            Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name(
                'dashboard'
            );

            // Data Committee
            Route::get('/admin/committee', [CommitteeController::class, 'index'])->name('committee');
            Route::post('/admin/committee', [CommitteeController::class, 'store'])->name("committee-add");
            Route::get('/admin/committee/{uuid}', [CommitteeController::class, 'edit']);
            Route::post('/admin/committee/update/{uuid}', [CommitteeController::class, 'update'])->name('committee.update');
            Route::delete('/admin/committee/delete/{uuid}', [CommitteeController::class, 'destroy'])->name("delete-committee");
            Route::get('/admin/committee', [CommitteeController::class, 'index'])->name('committee');

            // Data Wakif
            Route::get('/admin/wakif', [WakifController::class, 'index'])->name('wakif');
            Route::post('/admin/wakif', [WakifController::class, 'store'])->name("wakif-add");
            Route::get('/admin/wakif/{uuid}', [WakifController::class, 'edit']);
            Route::post('/admin/wakif/update/{uuid}', [WakifController::class, 'update'])->name('wakif.update');
            Route::delete('/admin/wakif/delete/{uuid}', [WakifController::class, 'destroy'])->name("delete-wakif");

            // Data Wakaf
            Route::get('/admin/wakaf', [WakafController::class, 'index'])->name('wakaf');
            Route::post('/admin/wakaf', [WakafController::class, 'store'])->name("wakaf-add");
            Route::get('/admin/wakaf/{uuid}', [WakafController::class, 'edit']);
            Route::post('/admin/wakaf/update/{uuid}', [WakafController::class, 'update'])->name('wakaf.update');
            Route::delete('/admin/wakaf/delete/{uuid}', [WakafController::class, 'destroy'])->name("delete-wakaf");

            // Data Transaksi
            Route::get('/admin/transaction', [TransactionWakafController::class, 'index'])->name('transaction');
            Route::post('/admin/transaction', [TransactionWakafController::class, 'store'])->name("transaction-add");
            Route::get('/admin/transaction/{uuid}', [TransactionWakafController::class, 'edit']);
            Route::post('/admin/transaction/update/{uuid}', [TransactionWakafController::class, 'update'])->name('transaction.update');
            Route::delete('/admin/transaction/delete/{uuid}', [TransactionWakafController::class, 'destroy'])->name("delete-transaction");
        }
    );


require __DIR__ . '/auth.php';
