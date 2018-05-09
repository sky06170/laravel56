<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
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
use App\Repositories\LineConfigRepository;
use App\Traits\LineLog;

class LineMessageService{

	use LineLog;

	protected $LINE_CHANNEL_ID;

	protected $LINE_CHANNEL_SECRET;

	protected $LINE_ACCESS_TOKEN;

	protected $LINE_USER_ID;

	protected $bot;

	protected $lineConfigRepository;

	public function __construct(LineConfigRepository $lineConfigRepository)
	{
		$this->LINE_CHANNEL_ID     = env('LINE_CHANNEL_ID');
		$this->LINE_CHANNEL_SECRET = env('LINE_CHANNEL_SECRET');
		$this->LINE_ACCESS_TOKEN   = env('LINE_ACCESS_TOKEN');
		$this->LINE_USER_ID        = env('LINE_USER_ID');
		
		$this->lineConfigRepo      = $lineConfigRepository;

		$this->boot();
	}

	/**
	 * 驅動Line Bot
	 * @return [type] [description]
	 */
	private function boot()
	{
		$this->checkAccessTokenInfo();
		$httpClient = new CurlHTTPClient($this->LINE_ACCESS_TOKEN);
		$this->bot = new LINEBot($httpClient, ['channelSecret' => $this->LINE_CHANNEL_SECRET]);
	}

	private function checkAccessTokenInfo()
	{
		$config = $this->lineConfigRepo->find();
		if($config == null){
			$dataArray               = $this->getAccessTokenInfo();
			$response                = $this->lineConfigRepo->createAccessToken($dataArray);
			$this->LINE_ACCESS_TOKEN = $response->access_token;
		}else{
			$today              = Carbon::now('Asia/Taipei')->getTimestamp();
			$accessTokenExpires = Carbon::createFromFormat('Y-m-d H:i:s', $config->updated_at, 'Asia/Taipei')->addSeconds($config->access_token_expires_in)->getTimestamp();
			if($today >= $accessTokenExpires){
				$dataArray               = $this->getAccessTokenInfo();
				$response                = $this->lineConfigRepo->updateAccessToken($dataArray);
				$this->LINE_ACCESS_TOKEN = $response->access_token;
			}
		}
	}

	private function getAccessTokenInfo()
	{
		$method = 'post';

		$url = 'https://api.line.me/v2/oauth/accessToken';

		$formParams = [
			'grant_type' => 'client_credentials',
			'client_id' => $this->LINE_CHANNEL_ID,
			'client_secret' => $this->LINE_CHANNEL_SECRET
		];

		$headers = [
			'Content-Type' => 'application/x-www-form-urlencoded'
		];

		$response = $this->sendRequest($method, $url, $formParams, $headers);

		$accessTokenInfo = json_decode($response->getBody()->getContents(),true);

		$dataArray = [
			'access_token' => $accessTokenInfo['access_token'],
			'access_token_expires_in' => $accessTokenInfo['expires_in']
		]; 

		return $dataArray;
	}

	/**
	 * 回覆訊息
	 * @param  string $replyToken
	 * @param  string|object $reply
	 * @param  string $type
	 * @return bool
	 */
	public function reply($webhookData, $reply = '', $type = 'msg')
	{
		$messageBuilder = $this->messageBuilder($reply, $type);

		if($messageBuilder !== null){
			$response = $this->bot->replyMessage($webhookData['replyToken'], $messageBuilder);
			if ($response->isSucceeded()) {
				$this->log(json_encode($reply), 'bot', $webhookData['replyToken'], $webhookData['source_userID'], $webhookData['source_groupID']);
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
					Log::info($response->getHTTPStatus() . ' ' . $response->getRawBody());
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

	/**
	 * 建構 webhook data
	 * @param  string $jsonString
	 * @return array
	 */
	public function getWebhookData($jsonString)
	{
		$jsonObject = json_decode($jsonString);

		$data = $jsonObject->events[0];

		$response = [
			'type' => $data->type,
			'replyToken' => $data->replyToken,
			'source_type' => $data->source->type,
			'source_userID' => $data->source->userId,
			'source_groupID' => ($data->source->type === 'group' ? $data->source->groupId : 0),
			'timestamp' => $data->timestamp,
			'message_type' => $data->message->type,
			'message_id' => $data->message->id,
			'message_text' => $data->message->text
		];

		$this->log($response['message_text'], 'user', $response['replyToken'], $response['source_userID'], $response['source_groupID']);

		return $response;
	}

	private function sendRequest($method, $uri, $formParams, $headers)
	{
	    $client = new Client;

	    return $client->request($method,$uri,[
	        'form_params' => $formParams,
	        'headers' => $headers
	    ]);
	}

	/**
	 * 模板提示訊息
	 * @return string
	 */
	private function templateMsg()
	{
		return '這訊息要用手機的Line才看的到哦!';
	}

}

?>