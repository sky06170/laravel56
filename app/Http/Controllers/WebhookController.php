<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LineMessageService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

	protected $lineMessageService;

    public function __construct(LineMessageService $lineMessageService)
    {
    	$this->lineMessageService = $lineMessageService;
    }

    public function line(Request $request)
    {
    	/*
    	{"events":[{"type":"message","replyToken":"c5ac90e439644608a5e77625c43791e6","source":{"userId":"U3a3a46e4ad6db382701add16bb9dfa9d","type":"user"},"timestamp":1525233462229,"message":{"type":"text","id":"7889061785751","text":"嗨"}}]}
    	 */
    	$jsonString = file_get_contents('php://input');

    	$webhookData = $this->lineMessageService->getWebhookData($jsonString);

    	$replyData = (new MessageService($webhookData))->getLineReply();

    	$this->lineMessageService->reply($webhookData['replyToken'], $replyData['reply'], $replyData['type']);
    }
}
