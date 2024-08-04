<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string|null $target
 * @property string $code
 * @property int|null $target_id
 * @property string|null $name
 * @property string|null $company
 * @property string|null $tax_number
 * @property string|null $tax_office
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $city
 * @property string|null $town
 * @property string|null $country
 * @property string|null $post_code
 * @property int $risk_limit
 * @property int $credit_limit
 * @property int $discount_rate
 * @property string|null $currency
 * @property string|null $identity_number
 * @property string|null $iban
 * @property int|null $seller_id
 * @property int|null $working_method_id
 * @property int|null $account_type_id
 * @property int|null $account_status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccountStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccountTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereDiscountRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereIdentityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account wherePostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereRiskLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereTaxOffice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereWorkingMethodId($value)
 * @mixin \Eloquent
 */
class Account extends Model
{
    use HasFactory;

    protected $guarded = [];
}
