<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LineMessageService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{

	protected $messageService,$lineMessageService;

    public function __construct(MessageService $messageService, LineMessageService $lineMessageService)
    {
        $this->messageService = $messageService;
    	$this->lineMessageService = $lineMessageService;
    }

    public function line(Request $request)
    {
    	$jsonString = file_get_contents('php://input');

    	$webhookData = $this->lineMessageService->getWebhookData($jsonString);

    	$replyData = $this->messageService->getLineReply($webhookData);

    	$this->lineMessageService->reply($webhookData, $replyData['reply'], $replyData['type']);
    }
}
