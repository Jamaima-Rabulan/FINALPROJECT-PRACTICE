<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * GET /api/v1/products
     * Returns every product (pagination and filters come in later steps).
     */
    public function index()
    {
        return Product::all();
    }
}