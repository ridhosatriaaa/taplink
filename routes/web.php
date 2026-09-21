<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrTagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Rute QrTagController
Route::controller(QrTagController::class)->group(function () {

    // Rute Utama Scan & Aktivasi QR
    Route::get('/qr/{qrTag}', 'scan')->name('qr.scan');
    Route::post('/qr/{qrTag}/activate', 'activate')->name('qr.activate');

    // Rute Alias /go/{qrTag}
    Route::get('/go/{qrTag}', 'scan')->name('qr.go_scan');
    Route::post('/go/{qrTag}/activate', 'activate')->name('qr.go_activate');

    // Rute Edit, Update & Verifikasi PIN
    Route::get('/qr/{qrTag}/edit', 'editForm')->name('qr.edit');
    Route::put('/qr/{qrTag}/update', 'update')->name('qr.update');
    Route::post('/qr/{qrTag}/verify-pin', 'verifyPin')->name('qr.verify-pin');

    // Rute Admin
    Route::prefix('admin')->as('admin.')->group(function () {
       Route::get('/', 'adminIndex')->name('index');
       Route::get('/export-qr-csv', 'exportQrCsv')->name('export-qr-csv');
       Route::post('/generate', 'adminGenerate')->name('generate');
    });

});