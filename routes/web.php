<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\HomeMiddleware;

use App\HTTP\Controllers\ImportXml;
use App\HTTP\Controllers\LogoutController;
use App\HTTP\Controllers\PDFController;
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

    Route::post('/xml', ImportXml::class);
    Route::get('/logout', LogoutController::class);

    Route::get('/pdf', PDFController::class);
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

