<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Middlewares
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\HomeMiddleware;

// Controllers
use App\HTTP\Controllers\PdfController;
use App\HTTP\Controllers\HintController;
use App\HTTP\Controllers\ViewDataController;
//use App\HTTP\Controllers\ImportXmlController;
//use App\HTTP\Controllers\AuthController;



// User zalogowany
Route::middleware([AuthMiddleware::class])->group(function () {
    // PATH
    $importXmlControllerPath = "App\HTTP\Controllers\ImportXmlController";

    // GET
    Route::get('/import', $importXmlControllerPath."@view")->name('import');
    Route::get('/logout', "AuthController@LogoutUser");
    Route::get("/getHint", HintController::class);
    
    // POST
    Route::post('/xml', $importXmlControllerPath."@import");
    Route::post('/pdf', PdfController::class);
    
    // MIX
    Route::match(array('POST', 'GET'), '/view', ViewDataController::class)->name('view');
});

// User niezalogowany
Route::middleware([HomeMiddleware::class])->group(function () {
    //PATH
    $authControllerPath = "App\HTTP\Controllers\AuthController";

    // GET
    Route::get('/', $authControllerPath."@ViewHomePage")->name('home');
    Route::get('/register', $authControllerPath."@ViewRegisterForm")->name('register');
    Route::get('/login', $authControllerPath."@ViewLoginForm")->name('login');

    // POST
    Route::post('/RegisterController', $authControllerPath."@RegisterUser");
    Route::post('/LoginController', $authControllerPath."@LoginUser");
});

