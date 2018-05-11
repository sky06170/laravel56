<?php

namespace App\Services\Logics;

use Carbon\Carbon;

class UserLogic{

	protected $userRepo, $standbyLogRepo;

	public function __construct($userRepo, $standbyLogRepo)
	{
		$this->userRepo = $userRepo;
		$this->standbyLogRepo = $standbyLogRepo;
	}

	public function analyticsMessage(&$reply, &$type, $message, $uid)
	{
		if(keyword_exists($message, ['註冊使用者'])){

			if($this->userRepo->findUserByUid($uid) != null){
				$reply = '你已註冊過了!';
			}else{
				$reply = '請輸入使用者名稱';

				$dataArr = [
					'uid'        => $uid,
					'log'        => 'register.user',
					'status'     => 'init',
					'created_at' => Carbon::now('Asia/Taipei')
				];
				$this->standbyLogRepo->log($dataArr);
			}
		}
	}

}

?>