<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InsuranceCompanyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Rutas de la API para InsuranceCompany en todos los métodos de forma grupal sin auth

Route::get('/insurance-companies', [InsuranceCompanyController::class, 'index']);
Route::post('/insurance-companies', [InsuranceCompanyController::class, 'store']);

// Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::get('/insurance-companies', [InsuranceCompanyController::class, 'index']);   
//     Route::post('/insurance-companies', [InsuranceCompanyController::class, 'store']);
//     Route::get('/insurance-companies/{insuranceCompany}', [InsuranceCompanyController::class, 'show']);
//     Route::put('/insurance-companies/{insuranceCompany}', [InsuranceCompanyController::class, 'update']);
//     Route::delete('/insurance-companies/{insuranceCompany}', [InsuranceCompanyController::class, 'destroy']);
// });