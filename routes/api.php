<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\VideoController;
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
    Route::get('/insurance-companies/{insuranceCompany}', [InsuranceCompanyController::class, 'show']);
    Route::delete('/insurance-companies/{insuranceCompany}', [InsuranceCompanyController::class, 'destroy']);
});
// Route::get('/blogs', [BlogController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{category}', [BlogController::class, 'index']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{blogModel}', [BlogController::class, 'update']);
    Route::get('/blogs/{blogModel}', [BlogController::class, 'show']);
    Route::delete('/blogs/{blogModel}', [BlogController::class, 'destroy']);
});

// Route::get('/blogs', [BlogController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/videos/{category}', [BlogController::class, 'index']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{blogModel}', [BlogController::class, 'update']);
    Route::get('/blogs/{blogModel}', [BlogController::class, 'show']);
    Route::delete('/blogs/{blogModel}', [BlogController::class, 'destroy']);
});


//Auth Routes with JWT
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');