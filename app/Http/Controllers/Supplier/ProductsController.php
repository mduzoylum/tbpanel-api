<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Filters\Supplier\ProductFilter;
use App\Http\Resources\Supplier\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __invoke(ProductFilter $filter, Request $request)
    {
        $query = Product::query();
        $query->where('supplier_id', auth()->user()->id);

        $query = $filter->apply($query);
        $data = $query->paginate($request->get('limit', 10));

        return $this->paginateResponse(ProductResource::collection($data), $data);
    }
}
