<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $order_type
 * @property string $address
 * @property string|null $company
 * @property string $phone
 * @property int $order_id
 * @property int $county_id
 * @property int $city_id
 * @property int $town_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereOrderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereTownId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderAddress extends Model
{
    use HasFactory;
}
