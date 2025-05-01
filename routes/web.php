<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvoiceController as Invoice;

// Route::get('/', function () {
//     return view('login/index');
// });
// Route::get('/welcome', function () {
//     return view('welcome');
// });

use App\Livewire\Auth\Register;

Route::get('/register', Register::class)->name('register');

Route::get('/downloadInvoice/{invoice_id}', [Invoice::class, 'download'])->name('download.invoice');



