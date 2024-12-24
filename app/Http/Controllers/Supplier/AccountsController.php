<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Filters\Supplier\ProductFilter;
use App\Http\Resources\Supplier\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function __invoke()
    {
        return "accounts";
    }


}
