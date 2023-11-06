<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get_invoices',[InvoiceController::class,'getInvoices']);
Route::get('/searchInvoice_',[InvoiceController::class,'searchInvoice_']);
Route::get('/create_invoices_',[InvoiceController::class,'createInvoices']);
Route::get('/customers',[CustomerController::class,'customers']);
Route::get('/products',[ProductController::class,'products']);
Route::post('/add_invoice',[InvoiceController::class,'store']);
Route::get('/show_invoice/{id}',[InvoiceController::class,'show']);
Route::get('/edit/{id}',[InvoiceController::class,'edit']);
Route::post('/try/{id}',[InvoiceController::class,'try']);
Route::get('/delete_invoice_items/{id}',[InvoiceController::class,'delete']);
Route::post('/update/{id}',[InvoiceController::class,'update']);
Route::get('/delete_invoice/{id}',[InvoiceController::class,'deleteInvoice']);