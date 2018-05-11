<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository{

	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function findUserByUid($uid)
	{
		return $this->user->where('uid', $uid)->first();
	}

	public function registerUser($dataArr = [])
	{
		return $this->user->create($dataArr);
	}

	public function updateUser($uid, $dataArr = [])
	{
		return $this->user->where('uid', $uid)->update($dataArr);
	}

}

?>