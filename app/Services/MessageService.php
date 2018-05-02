<?php

namespace App\Services;

use App\Services\Logics\CurrencyLogic;
use App\Services\Logics\NormalLogic;

class MessageService{

	protected $webhookData;

	protected $normalLogic;

	public function __construct($webhookData = [])
	{
		$this->webhookData = $webhookData;

		$this->currencyLogic = new CurrencyLogic();

		$this->normalLogic = new NormalLogic();
	}

	public function getLineReply()
	{
		$reply = '';
		$type  = 'msg';
		
		$message = $this->webhookData['message_text'];

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