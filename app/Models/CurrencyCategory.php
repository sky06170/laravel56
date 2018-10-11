<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyCategory extends Model
{
    protected $table = 'currency_categorys';

    protected $fillable = [
        'name', 'alias'
    ];
}
