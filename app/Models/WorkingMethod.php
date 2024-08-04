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
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingMethod whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkingMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WorkingMethod extends Model
{
    use HasFactory;

    protected $guarded = [];
}
