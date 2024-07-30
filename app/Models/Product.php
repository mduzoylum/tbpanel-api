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

    public function status()
    {
        return $this->belongsTo(AccountStatus::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function group()
    {
        return $this->belongsTo(ProductGroup::class);
    }

    public function storeStocks()
    {
        return $this->belongsToMany(Store::class, 'store_stocks')->withPivot('quantity');
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}
