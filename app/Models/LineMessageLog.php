<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineMessageLog extends Model
{
    protected $table = "line_message_log";

	protected $fillable = ['message', 'spokesman', 'replyToken', 'uid', 'gid'];
}
