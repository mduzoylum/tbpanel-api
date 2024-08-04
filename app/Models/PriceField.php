<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField query()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PriceField extends Model
{
    use HasFactory;
}
