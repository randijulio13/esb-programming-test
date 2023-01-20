<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::prefix('invoice')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/{invoice:id}', [InvoiceController::class, 'detail'])->name('invoice.detail');
    Route::post('/', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::patch('/{invoice:id}', [InvoiceController::class, 'update'])->name('invoice.update');

    Route::delete('/{invoice:id}', [InvoiceController::class, 'delete'])->name('invoice.delete');
});
