<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed target
 * @property mixed code
 * @property mixed target_id
 * @property mixed name
 * @property mixed company
 * @property mixed tax_number
 * @property mixed tax_office
 * @property mixed phone
 * @property mixed email
 * @property mixed address
 * @property mixed city
 * @property mixed town
 * @property mixed country
 * @property mixed post_code
 * @property mixed risk_limit
 * @property mixed credit_limit
 * @property mixed discount_rate
 * @property mixed currency
 * @property mixed identity_number
 * @property mixed iban
 * @property mixed seller_id
 * @property mixed working_method_id
 * @property mixed account_type_id
 * @property mixed account_status_id
 * @property mixed timestamps
 * @property mixed created_at
 */
class Account extends Model
{
    use HasFactory;

    protected $guarded = [];
}
