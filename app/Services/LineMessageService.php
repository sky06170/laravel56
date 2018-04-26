<?php

namespace App\Services;

use LINE\LINEBot\HTTPClient\CurlHTTPClient as Client;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
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
		$title = '喜歡的男人';
		$text = '請選出最喜歡的男人';
		$actions = [
			new MessageTemplateActionBuilder("Rick","我選Rick"),
			new MessageTemplateActionBuilder("rick","我選rick"),
			new MessageTemplateActionBuilder("蘇恆","我選蘇恆"),
			new MessageTemplateActionBuilder("恆恆","我選恆恆"),
		];
		$thumbnailImageUrl = "https://cdn.promodj.com/users-heads/00/00/01/96/70/milky-way-galaxy-wallpaper-1920x1080-1000x300%20%281%29_h592d.jpg";
		$button = new ButtonTemplateBuilder($title,$text, $thumbnailImageUrl, $actions);
		$message = new TemplateMessageBuilder("這訊息要用手機的賴才看的到哦!", $button);

		//123 - GID = C823b498d76e211d0fb3f944efb8d85ae
		//hy - GID = Cf08b180b5d0bfa65f34e865263653ed7
		$response = $this->bot->pushMessage('Cf08b180b5d0bfa65f34e865263653ed7', $message);
		if ($response->isSucceeded()) {
		    return true;
		}
		return false;
	}

}

?>