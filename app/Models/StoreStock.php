<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $store_id
 * @property int $product_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreStock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreStock extends Model
{
    use HasFactory;

    protected $guarded = [];
}
