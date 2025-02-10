<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InsuranceCompanyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Insurance Companies CRUD with JWT

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/insurance-companies', [InsuranceCompanyController::class, 'index']);
    Route::post('/insurance-companies', [InsuranceCompanyController::class, 'store']);
    Route::put('/insurance-companies/{insuranceCompany}', [InsuranceCompanyController::class, 'update']);
    Route::delete('/insurance-companies/{insuranceCompany}', [InsuranceCompanyController::class, 'destroy']);
});



//Auth Routes with JWT
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');