<?php

namespace App\Services;

use App\Services\Logics\TemplateLogic;
use App\Services\Logics\CurrencyLogic;
use App\Services\Logics\NormalLogic;
use Illuminate\Support\Facades\Log;

class MessageService{

	protected $webhookData;

	protected $normalLogic;

	public function __construct($webhookData = [])
	{
		$this->webhookData = $webhookData;

		$this->templateLogic = new TemplateLogic();

		$this->currencyLogic = new CurrencyLogic();

		$this->normalLogic = new NormalLogic();
	}

	public function getLineReply()
	{
		$reply = '';
		$type  = 'msg';
		
		$message = $this->webhookData['message_text'];

		if($reply === ''){
			$this->templateLogic->analyticsMessage($reply, $type, $message);
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