<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// TEMPORARY: delete in Step 7. Returns every product so we can see the seeded data.
Route::get('/products', function () {
    return Product::all();
});