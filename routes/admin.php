<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Traits\MyResponse;
use Illuminate\Support\Facades\Auth;

// ->middleware('auth:sanctum');
Route::post('/login', [UserController::class, 'login']);

Route::get('/error_login', fn () => MyResponse::returnError('not logged in.', 200))->name('login');


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/me', fn () => response()->json(['user' => Auth::user()]));
    Route::post('/add-customer', [CustomerController::class, 'addCustomer']);
    Route::post('/remove-customer', [CustomerController::class, 'removeCustomer']);
    Route::get('/get-all-customers',[CustomerController::class,'getAllCustomers']);
    Route::post('/add-export', [CustomerController::class, 'addExport']);
    Route::post('/add-import', [CustomerController::class, 'addImport']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/show', [ShowController::class, 'showAllOperation']);
    Route::get('/show/{id}', [ShowController::class, 'showCustomerOperationsById']);
    Route::post('/show/{name}', [ShowController::class, 'showCustomerOperationsByName']);
    Route::get('/show_imports', [ShowController::class, 'showImportsForCustomer']);
});
