<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStatisticsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Authentication routes
require __DIR__.'/auth.php';

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    Route::resource('rooms', RoomController::class);
    Route::resource('students', StudentController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('PaymentStatistics', PaymentStatisticsController::class);

    // Payment statistics route
    Route::get('/payment-statistics', [PaymentStatisticsController::class, 'index'])->name('paymentStatistics.index');

    // Additional payment routes
    Route::patch('/payments/{payment}/complete', [PaymentController::class, 'complete'])->name('payments.complete');
    Route::patch('/payments/{payment}/fail', [PaymentController::class, 'fail'])->name('payments.fail');
    Route::patch('/payments/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');

    // Soft delete routes
    Route::get('/payments/trashed', [PaymentController::class, 'trashed'])->name('payments.trashed');
    Route::patch('/payments/{id}/restore', [PaymentController::class, 'restore'])->name('payments.restore');
    Route::delete('/payments/{id}/force-delete', [PaymentController::class, 'forceDelete'])->name('payments.force-delete');

    // Booking payments and statistics
    Route::get('/bookings/{booking}/payments', [PaymentController::class, 'bookingPayments'])->name('payments.booking-payments');
    Route::get('/payments/statistics', [PaymentController::class, 'statistics'])->name('payments.statistics');

    // Additional booking routes
    Route::patch('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Soft delete routes
    Route::get('/bookings/trashed', [BookingController::class, 'trashed'])->name('bookings.trashed');
    Route::patch('/bookings/{id}/restore', [BookingController::class, 'restore'])->name('bookings.restore');
    Route::delete('/bookings/{id}/force-delete', [BookingController::class, 'forceDelete'])->name('bookings.force-delete');
});
