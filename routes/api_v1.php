<?php

use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

// apiResource creates the standard REST routes; only() keeps just the ones we need now.
// GET /api/v1/products -> ProductController@index
Route::apiResource('products', ProductController::class)->only(['index']);