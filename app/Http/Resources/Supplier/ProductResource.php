<?php

namespace App\Http\Resources\Supplier;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'target_quantity' => $this->target_quantity,
            'buying_price' => $this->buying_price,
            'list_price' => $this->list_price,
            'sale_price' =>$this->sale_price,
            'unit_id' => $this->unit_id,
        ];
    }

}
