<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineConfig extends Model
{
    protected $table = 'line_config';

    protected $fillable = ['access_token', 'access_token_expires_in'];
}
