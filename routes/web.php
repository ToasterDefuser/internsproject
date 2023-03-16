<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('page/home');
    return redirect('/import');
});
Route::get('/import', function () {
    return view('page/import');
})->name('import');
Route::get('/view', function () {
    return view('page/showData');
})->name('view');

Route::post('/xml', "App\HTTP\Controllers\ImportXml");