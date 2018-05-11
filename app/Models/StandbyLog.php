<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandbyLog extends Model
{
    protected $table = 'standby_log';

    protected $fillable = ['uid','log','status'];
}
