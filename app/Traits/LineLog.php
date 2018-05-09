<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Repositories\LineMessageLogRepository;

trait LineLog{

	public function log($message, $message_type = 'msg', $spokesman, $replyToken, $uid, $gid)
	{
		$created_at = Carbon::now('Asia/Taipei');

		$textlog = new LineMessageLogRepository();

		$data = compact('message', 'message_type', 'spokesman', 'replyToken', 'uid', 'gid', 'created_at');

		return $textlog->log($data);
	}

}

?>