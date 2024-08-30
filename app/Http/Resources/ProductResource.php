<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $demand_quantity = $this->quantity - $this->target_quantity;
        if($demand_quantity < 0){
            $demand_quantity = 0;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'model_code' => $this->model_code,
            'stock_code' => $this->stock_code,
            'quantity' => $this->quantity,
            'target_quantity' => $this->target_quantity,
            'demand_quantity' => $demand_quantity,
            'order_quantity' => OrderProduct::where('product_id', $this->id)->sum('quantity'),
            'entered_quantity' => 0,
            'sale_quantity' => 0,
            'status' => $this->status,
            'price' => $this->price,
            'attributes' => $this->attributes ?? null,
            'currency' => $this->currency,
            'unit' => $this->unit,
            'prices' => $this->prices,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
