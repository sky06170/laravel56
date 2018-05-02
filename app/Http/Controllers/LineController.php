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
    		$response = $this->lineMessageService->push($message);
    		return response()->json(['status' => $response]);
    	}
    	return response()->json(['status' => false]);
    }

    public function sendImage(Request $request)
    {
    	if($request->ajax()){
            $response = $this->lineMessageService->push('https://cdn2.ettoday.net/images/2709/2709691.jpg','img');
    		return response()->json(['status' => $response]);
    	}
    	return response()->json(['status' => false]);
    }

    public function sendButtonTemplate(Request $request)
    {
    	if($request->ajax()){
            $data = [
                'title' => '運動清單',
                'text'  => '請選出喜歡的運動方式',
                'thumbnailImageUrl' => 'https://cdn.promodj.com/users-heads/00/00/01/96/70/milky-way-galaxy-wallpaper-1920x1080-1000x300%20%281%29_h592d.jpg',
                'actionBuilders' => [
                        [
                            'label' => '游泳',
                            'text'  => '我選游泳'
                        ],
                        [
                            'label' => '跑步',
                            'text' => '我選跑步'
                        ],
                        [
                            'label' => '瑜珈',
                            'text' => '我選瑜珈'
                        ],
                ]
            ];
    		$response = $this->lineMessageService->push($data, 'button');
    		return response()->json(['status' => $response]);
    	}
    	return response()->json(['status' => false]);
    }

    public function sendConfirmTemplate(Request $request)
    {
    	if($request->ajax()){
            $data = [
                'text' => '今晚要打free fire嗎?',
                'actionBuilders' => [
                        [
                            'label' => 'Yes',
                            'text'  => 'yes'
                        ],
                        [
                            'label' => 'No',
                            'text' => 'no'
                        ],
                ]
            ];
    		$response = $this->lineMessageService->push($data, 'confirm');
    		return response()->json(['status' => $response]);
    	}
    	return response()->json(['status' => false]);
    }

    public function sendCarouselTemplate(Request $request)
    {
    	if($request->ajax()){
    		$columns = [
    			[
    				'title' => '運動清單',
    				'text' => '請選出喜歡的運動方式',
    				'thumbnailImageUrl' => 'https://cdn.promodj.com/users-heads/00/00/01/96/70/milky-way-galaxy-wallpaper-1920x1080-1000x300%20%281%29_h592d.jpg',
    				'actionBuilders' => [
											[
												'label' => '游泳',
												'text'  => '我選游泳'
											],
											[
												'label' => '跑步',
												'text' => '我選跑步'
											],
											[
												'label' => '瑜珈',
												'text' => '我選瑜珈'
											],
									],
    			],
    			[
    				'title' => '音樂清單',
    				'text' => '請選出喜歡的音樂方式',
    				'thumbnailImageUrl' => 'https://static1.squarespace.com/static/57dea572579fb3ea46810d43/t/580e06ddd1758e7907272fdc/1477314272980/Background+B+1000x300.png?format=1000w',
    				'actionBuilders' => [
											[
												'label' => '古典',
												'text'  => '我選古典'
											],
											[
												'label' => '搖滾',
												'text' => '我選搖滾'
											],
											[
												'label' => '民謠',
												'text' => '我選民謠'
											],
									],
    			],
    		];
    		$response = $this->lineMessageService->push($columns, 'carousel_button');
    		return response()->json(['status' => $response]);
    	}
    	return response()->json(['status' => false]);
    }

}
