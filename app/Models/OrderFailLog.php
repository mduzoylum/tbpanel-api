<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $code
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFailLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFailLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFailLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFailLog whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFailLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFailLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFailLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFailLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderFailLog extends Model
{
    use HasFactory;

    protected $guarded = [];
}
