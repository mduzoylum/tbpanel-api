<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $attribute_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttributeOption whereUpdatedAt($value)
 */
class AttributeOption extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

}
