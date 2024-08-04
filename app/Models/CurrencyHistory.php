<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $date
 * @property string $rate
 * @property int $currency_id
 * @property int $default_currency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory whereDefaultCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CurrencyHistory extends Model
{
    use HasFactory;

    protected $guarded = [];
}
