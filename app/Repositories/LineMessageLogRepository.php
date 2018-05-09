<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LineMessageLogRepository{

	private function db()
	{
		return DB::table('line_message_log');
	}

	public function log($dataArray = [])
	{
		return $this->db()->insert($dataArray);
	}

}

?>