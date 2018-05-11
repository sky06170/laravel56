<?php

namespace App\Services\Logics;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StandbyLogLogic{

	protected $standbyLogRepo, $userRepo;

	public function __construct($standbyLogRepo, $userRepo)
	{
		$this->standbyLogRepo = $standbyLogRepo;
		$this->userRepo       = $userRepo;
	}

	public function analyticsMessage(&$reply, &$type, $message, $uid)
	{
		$data = $this->standbyLogRepo->findLog($uid);

		if($data != null){

			switch($data->log){

				case 'register.user':

					$this->registerUser($reply, $type, $message, $uid, $data);

					break;
				default:
					break;

			}

		}
	}

	private function registerUser(&$reply, &$type, $message, $uid, $data)
	{
		try{

			DB::beginTransaction();

			$dataArr = [
				'uid' => $uid,
				'name' => $message,
				'status' => 1
			];

			$response = $this->userRepo->registerUser($dataArr);
			$this->standbyLogRepo->delete($data->id);

			if($response){
				$reply = '註冊成功!';
			}else{
				$reply = '註冊失敗!';
			}

			DB::commit();

		}catch(\Exception $e){

			$reply = '註冊失敗!';

			DB::rollback();

		}
	}

}

?>