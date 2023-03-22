<?php

use Illuminate\Support\Facades\Route;

// Middlewares
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\HomeMiddleware;

// Controllers
use App\HTTP\Controllers\ImportXmlController;
use App\HTTP\Controllers\LogoutController;
use App\HTTP\Controllers\PdfController;
use App\HTTP\Controllers\RegisterController;
use App\HTTP\Controllers\LoginController;

// User zalogowany
Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/import', function () {
        return view('page/import');
    })->name('import');
    
    Route::get('/view', function () {
        return view('page/showData');
    })->name('view');

    Route::post('/xml', ImportXmlController::class);
    Route::get('/logout', LogoutController::class);
    Route::post('/pdf', PdfController::class);
});

// User niezalogowany
Route::middleware([HomeMiddleware::class])->group(function () {
    Route::get('/', function () {
        return view('page/home');
    })->name('home');
    
    Route::get('/register', function () {
        return view('page/register');
    })->name('register');

    Route::get('/login', function () {
        return view('page/login');
    })->name('login');

    Route::post('/RegisterController', RegisterController::class);
    Route::post('/LoginController', LoginController::class);
});

