<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::post('/products', [ProductController::class, 'storeApi'])->name('product.storeApi');
Route::get('/products', [ProductController::class, 'indexApi'])->name('product.indexApi');
