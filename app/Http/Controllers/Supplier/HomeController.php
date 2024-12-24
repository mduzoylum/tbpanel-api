<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Filters\Supplier\ProductFilter;
use App\Http\Resources\Supplier\ProductResource;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        //products
        //status
        //products_status
        /**
         * [
        'pending' => ['title' => 'Bekleniyor', 'color' => 'blue', 'bgColor' => '#b2e5fc'],
        'coming' => ['title' => 'Geliyor', 'color' => 'green', 'bgColor' => '#dcedc8'],
        'old_season' => ['title' => 'Eski Sezon', 'color' => 'teal', 'bgColor' => '#b2dfdb'],
        'removed' => ['title' => 'Üretimden Kalktı', 'color' => 'danger', 'bgColor' => '#fcc6c2'],
        'closed' => ['title' => 'Talep Kapalı', 'color' => 'orange', 'bgColor' => '#ffe0b2']
    ]
         */
        $products = Supplier::with(['products', 'products.status'])->paginate(10);
        return $this->paginateResponse(ProductResource::collection($products), $products);
    }


}
