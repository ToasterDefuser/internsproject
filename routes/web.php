<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('page/home');
});
Route::get('/test', function () {
    return view('page/test');
});
Route::get('/import', function () {
    return view('page/import');
});
Route::get('/view', function () {
    return view('page/showData');
});

Route::post('/xml', "App\HTTP\Controllers\ImportXml");