<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $address
 * @property string|null $company
 * @property string $phone
 * @property int $customer_id
 * @property int $county_id
 * @property int $city_id
 * @property int $town_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereTownId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerAddress extends Model
{
    use HasFactory;
}
