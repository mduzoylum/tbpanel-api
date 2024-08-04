<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $title
 * @property string $debt
 * @property string $credit
 * @property string $currency
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction whereDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerTransaction extends Model
{
    use HasFactory;
}
