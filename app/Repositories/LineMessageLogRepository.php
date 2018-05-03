<?php

namespace App\Repositories;

use App\Models\LineMessageLog;
use Illuminate\Support\Facades\DB;

class LineMessageLogRepository{

	protected $lineMessageLog;

	public function __construct(LineMessageLog $lineMessageLog)
	{
		$this->lineMessageLog = $lineMessageLog;
	}

	private function db()
	{
		return DB::table('line_message_log');
	}

	public function create($dataArray = [])
	{
		return $this->lineMessageLog->create($dataArray);
	}

}

?>