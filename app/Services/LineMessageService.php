<?php

namespace App\Services;

use LINE\LINEBot\HTTPClient\CurlHTTPClient as Client;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;

class LineMessageService{

	protected $LINE_CHANNEL_ID;

	protected $LINE_CHANNEL_SECRET;

	protected $LINE_ACCESS_TOKEN;

	protected $LINE_USER_ID;

	protected $bot;

	public function __construct()
	{
		$this->LINE_CHANNEL_ID     = env('LINE_CHANNEL_ID');
		$this->LINE_CHANNEL_SECRET = env('LINE_CHANNEL_SECRET');
		$this->LINE_ACCESS_TOKEN   = env('LINE_ACCESS_TOKEN');
		$this->LINE_USER_ID        = env('LINE_USER_ID');

		$this->boot();
	}

	private function boot()
	{
		$httpClient = new Client($this->LINE_ACCESS_TOKEN);
		$this->bot = new LINEBot($httpClient, ['channelSecret' => $this->LINE_CHANNEL_SECRET]);
	}

	public function sendTextMessage($message = '')
	{
		$textMessageBuilder = new TextMessageBuilder($message);
		$response = $this->bot->pushMessage($this->LINE_USER_ID, $textMessageBuilder);
		if ($response->isSucceeded()) {
		    return true;
		}
		return false;
	}

	public function sendImageMessage($originalContentUrl = '', $previewImageUrl = '')
	{
		$imageMessageBuilder = new ImageMessageBuilder($originalContentUrl, $previewImageUrl);
		$response = $this->bot->pushMessage($this->LINE_USER_ID, $imageMessageBuilder);
		if ($response->isSucceeded()) {
		    return true;
		}
		return false;
	}

	public function sendButtonTemplateMessage($title = '', $text = '', $thumbnailImageUrl = '', $actionBuilders = [])
	{
		$actions = [];
		foreach($actionBuilders as $val){
			array_push($actions,new MessageTemplateActionBuilder($val['label'],$val['text']));
		}
		$button = new ButtonTemplateBuilder($title,$text, $thumbnailImageUrl, $actions);
		$message = new TemplateMessageBuilder("這訊息要用手機的賴才看的到哦!", $button);

		$response = $this->bot->pushMessage($this->LINE_USER_ID, $message);
		if ($response->isSucceeded()) {
		    return true;
		}
		//echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
		return false;
	}

	public function sendConfirmTemplateMessage($text = '', $actionBuilders = [])
	{
		$actions = [];
		foreach($actionBuilders as $val){
			array_push($actions,new MessageTemplateActionBuilder($val['label'],$val['text']));
		}
		$confirm = new ConfirmTemplateBuilder($text, $actions);
		$message = new TemplateMessageBuilder("這訊息要用手機的賴才看的到哦!", $confirm);

		$response = $this->bot->pushMessage($this->LINE_USER_ID, $message);
		if ($response->isSucceeded()) {
		    return true;
		}
		//echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
		return false;
	}

	public function sendCarouselTemplateMessage($columns = [])
	{
		$columnArray = [];
		foreach($columns as $column){
			$actions = [];
			foreach($column['actionBuilders'] as $val){
				array_push($actions,new MessageTemplateActionBuilder($val['label'],$val['text']));
			}
			$button = new ButtonTemplateBuilder($column['title'],$column['text'], $column['thumbnailImageUrl'], $actions);
			array_push($columnArray,$button);
		}
		$carousel = new CarouselTemplateBuilder($columnArray);

		$message = new TemplateMessageBuilder("這訊息要用手機的賴才看的到哦!", $carousel);

		$response = $this->bot->pushMessage($this->LINE_USER_ID, $message);
		if ($response->isSucceeded()) {
		    return true;
		}

		return false;
	}

}

?>