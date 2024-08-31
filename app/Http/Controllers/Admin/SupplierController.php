<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function get(Request $request)
    {
        $data = Supplier::search()->paginate($request->get('limit', 10));
        return $this->paginateResponse(SupplierResource::collection($data), $data);
    }

}
