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
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroup whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductGroup extends Model
{
    use HasFactory;

    protected $guarded = [];
}
