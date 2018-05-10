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
		try{

			DB::beginTransaction();

			$response = $this->db()->insert($dataArray);

			DB::commit();

			return $response;

		}catch(\Exception $e){

			DB::rollback();

		}
	}

}

?>