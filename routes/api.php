<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('invoice')->group(function () {
    Route::get('/', [InvoiceController::class, 'getInvoices']);
    Route::get('/{invoice:id}', [InvoiceController::class, 'getInvoiceById']);
});

Route::get('/item', [ItemController::class, 'getItems']);
