<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRecord extends Model
{
    protected $table = 'currency_records';

    protected $fillable = [
        'currency_category_id', 'bank', 'immediate_buy', 'immediate_sell', 'cash_buy', 'cash_sell'
    ];
}
