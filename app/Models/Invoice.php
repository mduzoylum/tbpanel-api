<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $code
 * @property string $amount_total
 * @property string $tax_total
 * @property string $discount_total
 * @property string $currency
 * @property int $invoice_type_id
 * @property string $invoice_type_code
 * @property int|null $account_id
 * @property string|null $account_code
 * @property string|null $store_code
 * @property string|null $seller_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAccountCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAmountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDiscountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceTypeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereSellerCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereStoreCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereTaxTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 */
class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function invoiceType()
    {
        return $this->belongsTo(InvoiceType::class);
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class);
    }
}
