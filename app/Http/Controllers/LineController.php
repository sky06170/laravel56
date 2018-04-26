<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LineMessageService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class LineController extends Controller
{

	protected $lineMessageService;

    public function __construct(LineMessageService $lineMessageService)
    {
    	$this->lineMessageService = $lineMessageService;
    }

    public function index()
    {
    	return View('line');
    }

    public function sendText(Request $request)
    {
    	if($request->ajax()){
    		$message = $request->input('message','');
    		$response = $this->lineMessageService->sendTextMessage($message);
    		return response()->json(['status' => $response]);
    	}
    	return response()->json(['status' => false]);
    }

    public function sendImage(Request $request)
    {
    	if($request->ajax()){
			$originalContentUrl = "https://cdn2.ettoday.net/images/2709/2709691.jpg";
			$previewImageUrl = "https://cdn2.ettoday.net/images/2709/2709691.jpg";
    		$response = $this->lineMessageService->sendImageMessage($originalContentUrl, $previewImageUrl);
    		return response()->json(['status' => $response]);
    	}
    	return response()->json(['status' => false]);
    }

    public function sendButtonTemplate(Request $request)
    {
    	//if($request->ajax()){
			$title = 'Menu';
			$text = 'Please select';
			$thumbnailImageUrl = 'https://cdn.promodj.com/users-heads/00/00/01/96/70/milky-way-galaxy-wallpaper-1920x1080-1000x300%20%281%29_h592d.jpg';
			$actionBuilders = [
				[
					'type' => 'postback',
					'label' => 'Buy',
					'data' => 'action=buy&itemid=123'
				],
				[
					'type' => 'postback',
					'label' => 'Add to cart',
					'data' => 'action=add&itemid=123'
				]
			];
    		$response = $this->lineMessageService->sendButtonTemplateMessage($title, $text, $thumbnailImageUrl, $actionBuilders);
    		return response()->json(['status' => $response]);
    	//}
    	//return response()->json(['status' => false]);
    }

}
