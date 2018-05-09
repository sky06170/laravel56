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
    	$jsonString = file_get_contents('php://input');

    	$webhookData = $this->lineMessageService->getWebhookData($jsonString);

    	$replyData = (new MessageService($webhookData))->getLineReply();

    	$this->lineMessageService->reply($webhookData, $replyData['reply'], $replyData['type']);
    }
}
