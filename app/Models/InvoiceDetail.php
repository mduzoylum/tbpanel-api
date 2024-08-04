<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $product_code
 * @property float $price
 * @property float $tax_rate
 * @property int $quantity
 * @property int|null $order_id
 * @property int|null $product_id
 * @property string $amount_total
 * @property string $tax_total
 * @property string $discount_total
 * @property string $currency
 * @property string $unit_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereAmountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereDiscountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereProductCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereTaxTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereUnitName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InvoiceDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
}
