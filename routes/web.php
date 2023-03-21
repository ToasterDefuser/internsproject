<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\HomeMiddleware;

// User zalogowany
Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/import', function () {
        return view('page/import');
    })->name('import');
    
    Route::get('/view', function () {
        return view('page/showData');
    })->name('view');

    Route::post('/xml', "App\HTTP\Controllers\ImportXml");
    Route::get('/logout', "App\HTTP\Controllers\LogoutController");

    Route::get('/pdf', "App\HTTP\Controllers\PDFController");
    Route::get('/pdft', function () {
        return view('pdf');
    })->name('pdft');
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

    Route::post('/RegisterController', "App\HTTP\Controllers\RegisterController");
    Route::post('/LoginController', "App\HTTP\Controllers\LoginController");
});

