<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * @property string $stock_code
 * @property string $name
 * @property string $description
 * @property string $model_code
 * @property int $quantity
 * @property int $box_quantity
 * @property int $target_quantity
 * @property float $buying_price
 * @property float $list_price
 * @property float $sale_price
 * @property string $currency
 * @property string $barcode
 * @property int $tax_rate
 * @property int $status_id
 * @property int $unit_id
 * @property int $supplier_id
 * @property int $brand_id
 * @property int $season_id
 * @property int $type_id
 * @property int $group_id
 */
class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
}
