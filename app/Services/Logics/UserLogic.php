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

	private function setStandbyLog($uid, $log)
	{
		$dataArr = [
				'uid'        => $uid,
				'log'        => $log,
				'status'     => 'init',
				'created_at' => Carbon::now('Asia/Taipei')
			];

		$this->standbyLogRepo->log($dataArr);
	}

	public function analyticsMessage(&$reply, &$type, $message, $uid)
	{
		if(keyword_exists($message, ['註冊使用者','register user'])){
			$this->registerUser($reply, $uid);
		}elseif(keyword_exists($message, ['更新使用者名稱','更新使用者姓名','update user name'])){
			$this->updateUserName($reply, $uid);
		}elseif(keyword_exists($message, ['顯示使用者名稱','顯示使用者姓名','show user name'])){
			$this->showUserName($reply, $uid);
		}
	}

	private function registerUser(&$reply, $uid)
	{
		if($this->userRepo->findUserByUid($uid) != null){
			$reply = '你已註冊過了!';
		}else{
			$reply = '請輸入使用者名稱';
			$this->setStandbyLog($uid, 'register.user');
		}
	}

	private function updateUserName(&$reply, $uid)
	{
		if($this->userRepo->findUserByUid($uid) != null){
			$reply = '請輸入欲更新的使用者名稱';
			$this->setStandbyLog($uid, 'update.user.name');
		}else{
			$reply = '查詢不到您的資料!';
		}
	}

	private function showUserName(&$reply, $uid)
	{
		$response = $this->userRepo->findUserByUid($uid);
		if($response != null){
			$reply = $response->name;
		}else{
			$reply = '查詢不到您的資料!';
		}
	}

}

?>