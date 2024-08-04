<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $planned_date
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyRecord wherePlannedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyRecord whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupplyRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SupplyRecord extends Model
{
    use HasFactory;
}
