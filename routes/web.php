<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

//InsurersView
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/insurers', function () {
        return Inertia::render('Dashboard');
    })->name('insurers');
});

//BlogView
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/blogs', function () {
        return Inertia::render('Dashboard');
    })->name('blogs');
});

//VideoView
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/videos', function () {
        return Inertia::render('Dashboard');
    })->name('videos');
});

//TurismoView
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/turismo', function () {
        return Inertia::render('Dashboard');
    })->name('turismo');
});

//InstalacionesView
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/instalaciones', function () {
        return Inertia::render('Dashboard');
    })->name('instalaciones');
});

//MetodosPagoView
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/metodos_pago', function () {
        return Inertia::render('Dashboard');
    })->name('metodos_pago');
});

//CategoriasView
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/categorias', function () {
        return Inertia::render('Dashboard');
    })->name('categorias');
});