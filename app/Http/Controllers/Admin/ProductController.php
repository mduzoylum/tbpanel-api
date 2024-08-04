<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function get(Request $request)
    {

        $data = Product::paginate(10);

        return $this->paginateResponse(ProductResource::collection($data), $data);
    }

    public function create(Request $request)
    {
        return response()->json([
            'message' => 'ProductController create method'
        ]);
    }

    public function update(Request $request)
    {
        return response()->json([
            'message' => 'ProductController update method'
        ]);
    }

    public function delete(Request $request)
    {
        return response()->json([
            'message' => 'ProductController delete method'
        ]);
    }


}
