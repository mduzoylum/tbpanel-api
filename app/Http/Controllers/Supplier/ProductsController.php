<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function get()
    {
        $products = Product::whereSupplierId(auth()->user()->id)->limit(10)->get();
        return response()->json($products);
    }
}
