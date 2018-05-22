<?php

namespace App\Services;

use App\Services\Logics\StandbyLogLogic;
use App\Services\Logics\TemplateLogic;
use App\Services\Logics\UserLogic;
use App\Services\Logics\JuksyLogic;
use App\Services\Logics\CurrencyLogic;
use App\Services\Logics\NormalLogic;
use Illuminate\Support\Facades\Log;
use App\Repositories\UserRepository;
use App\Repositories\StandbyLogRepository;

class MessageService{

	protected $webhookData;

	protected $normalLogic;

	public function __construct(UserRepository $userRepo, StandbyLogRepository $standbyLogRepo)
	{
		$this->standbyLogLogic = new StandbyLogLogic($standbyLogRepo, $userRepo);

		$this->templateLogic = new TemplateLogic();

		$this->userLogic = new UserLogic($userRepo, $standbyLogRepo);

		$this->juksyLogic = new JuksyLogic();

		$this->currencyLogic = new CurrencyLogic();

		$this->normalLogic = new NormalLogic();
	}

	public function getLineReply($webhookData = [])
	{
		$this->webhookData = $webhookData;

		$reply = '';
		$type  = 'msg';
		
		$message = $this->webhookData['message_text'];

		if($reply === ''){
			$this->standbyLogLogic->analyticsMessage($reply, $type, $message, $this->webhookData['source_userID']);
		}

		if($reply === ''){
			$this->templateLogic->analyticsMessage($reply, $type, $message);
		}

		if($reply === ''){
			$this->userLogic->analyticsMessage($reply, $type, $message, $this->webhookData['source_userID']);
		}

		if($reply === ''){
			$this->juksyLogic->analyticsMessage($reply, $type, $message);
		}

		if($reply === ''){
			$this->currencyLogic->analyticsMessage($reply, $type, $message);
		}

		if($reply === ''){
			$this->normalLogic->analyticsMessage($reply, $type, $message);
		}

		return [
			'reply' => $reply,
			'type'  => $type
		];
	}

}

?>