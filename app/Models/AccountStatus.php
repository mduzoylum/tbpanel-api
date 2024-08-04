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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStatus whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AccountStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
}
