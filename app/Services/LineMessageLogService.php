<?php

namespace App\Services;

use App\Repositories\LineMessageLogRepository;

class LineMessageLogService{

	protected $lineMessageLogRepository;

	public function __construct(LineMessageLogRepository $lineMessageLogRepository)
	{
		$this->lineMessageLogRepository = $lineMessageLogRepository;
	}

	public function log($message, $spokesman, $replyToken, $uid, $gid)
	{
		$data = compact('message', 'spokesman', 'replyToken', 'uid', 'gid');

		return $this->lineMessageLogRepository->create($data);
	}

}

?>