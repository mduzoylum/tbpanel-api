<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 *
 *
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
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brand $brand
 * @property-read \App\Models\ProductGroup $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductPrice> $prices
 * @property-read int|null $prices_count
 * @property-read \App\Models\Season $season
 * @property-read \App\Models\AccountStatus|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Store> $storeStocks
 * @property-read int|null $store_stocks_count
 * @property-read \App\Models\Supplier $supplier
 * @property-read \App\Models\ProductType $type
 * @property-read \App\Models\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBoxQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBuyingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereListPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereModelCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTargetQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
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
        return $this->hasMany(ProductPrice::class)->with('priceField');
    }

    public function attributes()
    {

        // get attributes with attribute name and option name
        return $this->hasMany(ProductAttribute::class)->with('attribute', 'attributeOption');

        return $this->hasMany(ProductAttribute::class);
    }
}
