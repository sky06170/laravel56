<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient as Client;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
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

	/**
	 * 回覆訊息
	 * @param  string $replyToken
	 * @param  string|object $reply
	 * @param  string $type
	 * @return bool
	 */
	public function reply($replyToken, $reply = '', $type = 'msg')
	{
		$messageBuilder = $this->messageBuilder($reply, $type);

		if($messageBuilder !== null){
			$response = $this->bot->replyMessage($replyToken, $messageBuilder);
			if ($response->isSucceeded()) {
			    return true;
			}else{
				Log::info($response->getHTTPStatus() . ' ' . $response->getRawBody());
			}
		}
		return false;
	}

	/**
	 * 推播訊息
	 * @param  string|object $message
	 * @param  string $type 
	 * @return bool
	 */
	public function push($message, $type = 'msg')
	{
		try{
			$messageBuilder = $this->messageBuilder($message, $type);
			if($messageBuilder !== null){
				$response = $this->bot->pushMessage($this->LINE_USER_ID, $messageBuilder);
				if ($response->isSucceeded()) {
				    return true;
				}else{
					dd($response->getHTTPStatus() . ' ' . $response->getRawBody());
				}
			}
			return false;
		}catch(Exception $e){
			dd($e->getMessage());
			return false;
		}
	}

	/**
	 * 建立訊息格式
	 * @param  string|object $message
	 * @param  string $type
	 * @return object $messageBuilder
	 */
	private function messageBuilder($message, $type)
	{
		if($message == null){
			return null;
		}

		switch($type){
			case 'msg':
				$messageBuilder = new TextMessageBuilder($message);
				break;
			case 'img':
				$originalContentUrl = $message;
				$previewImageUrl    = $message;
				$messageBuilder     = new ImageMessageBuilder($originalContentUrl, $previewImageUrl);
				break;
			case 'button': 
				$actions = [];
				foreach($message['actionBuilders'] as $val){
					array_push($actions,new MessageTemplateActionBuilder($val['label'],$val['text']));
				}
				$button         = new ButtonTemplateBuilder($message['title'], $message['text'], $message['thumbnailImageUrl'], $actions);
				$messageBuilder = new TemplateMessageBuilder($this->templateMsg(), $button);
				break;
			case 'confirm':
				$actions = [];
				foreach($message['actionBuilders'] as $val){
					array_push($actions,new MessageTemplateActionBuilder($val['label'], $val['text']));
				}
				$confirm        = new ConfirmTemplateBuilder($message['text'], $actions);
				$messageBuilder = new TemplateMessageBuilder($this->templateMsg(), $confirm);
				break;
			case 'carousel_button':
				$columnArray = [];
				foreach($message as $column){
					$actions = [];
					foreach($column['actionBuilders'] as $val){
						array_push($actions, new MessageTemplateActionBuilder($val['label'], $val['text']));
					}
					$item = new ButtonTemplateBuilder($column['title'],$column['text'], $column['thumbnailImageUrl'], $actions);
					array_push($columnArray,$item);
				}
				$carousel       = new CarouselTemplateBuilder($columnArray);
				$messageBuilder = new TemplateMessageBuilder($this->templateMsg(), $carousel);
				break;
			case 'carousel_image':
				$columnArray = [];
				foreach($message as $column){
					Log::info('label = '.$column['actionBuilder']['label']);
					$action = new UriTemplateActionBuilder($column['actionBuilder']['label'], $column['actionBuilder']['uri']);
					$item = new ImageCarouselColumnTemplateBuilder($column['imageUrl'], $action);
					array_push($columnArray,$item);
				}
				$carousel       = new ImageCarouselTemplateBuilder($columnArray);
				$messageBuilder = new TemplateMessageBuilder($this->templateMsg(), $carousel);
				break;
			default:
				$messageBuilder = null;
				break;
		}

		return $messageBuilder;
	}

	public function getWebhookData($jsonString)
	{
		$jsonObject = json_decode($jsonString);

		$data = $jsonObject->events[0];

		return [
			'type' => $data->type,
			'replyToken' => $data->replyToken,
			'source_userID' => $data->source->userId,
			'source_type' => $data->source->type,
			'timestamp' => $data->timestamp,
			'message_type' => $data->message->type,
			'message_id' => $data->message->id,
			'message_text' => $data->message->text
		];
	}

	private function templateMsg()
	{
		return '這訊息要用手機的Line才看的到哦!';
	}

}

?>