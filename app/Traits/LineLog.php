<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Repositories\LineMessageLogRepository;

trait LineLog{

	public function log($message, $spokesman, $replyToken, $uid, $gid)
	{
		$created_at = Carbon::now('Asia/Taipei');

		$textlog = new LineMessageLogRepository();

		$data = compact('message', 'spokesman', 'replyToken', 'uid', 'gid', 'created_at');

		return $textlog->log($data);
	}

}

?>