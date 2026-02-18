<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EvaluatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/customer/appointment', [CustomerController::class, 'appointment'])->name('customer.appointment');
Route::post('/customer/appointment', [CustomerController::class, 'storeAppointment'])->name('customer.store-appointment');
Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');

Route::get('/payment/{case}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{case}', [PaymentController::class, 'process'])->name('payment.process');

Route::get('/evaluator', [EvaluatorController::class, 'index'])->name('evaluator.index');
Route::get('/evaluator/{case}', [EvaluatorController::class, 'show'])->name('evaluator.show');
Route::put('/evaluator/{case}', [EvaluatorController::class, 'update'])->name('evaluator.update');
Route::post('/evaluator/{case}/action', [EvaluatorController::class, 'action'])->name('evaluator.action');

Route::get('/report/{case}', [ReportController::class, 'show'])->name('report.show');
Route::get('/report/{case}/download', [ReportController::class, 'download'])->name('report.download');
