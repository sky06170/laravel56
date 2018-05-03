<?php

namespace App\Services\Logics;

use Illuminate\Support\Facades\Log;

class TemplateLogic{

	public function __construct(){}

	public function analyticsMessage(&$reply, &$type, $message)
	{
		if(keyword_exists($message,['測試按鈕模板','test button template'])){
			$reply = $this->testButtonTemplate();
			$type  = 'button';
		}elseif(keyword_exists($message,['測試確認模板','test confirm template'])){
			$reply = $this->testConfirmTemplate();
			$type  = 'confirm';
		}elseif(keyword_exists($message,['test carousel template'])){
			$reply = $this->testCarouselTemplate();
			$type  = 'carousel_button';
		}elseif(keyword_exists($message,['test image carousel template'])){
			$reply = $this->testImageCarouselTemplate();
			$type  = 'carousel_image';
		}
	}

	private function testButtonTemplate()
	{
		return [
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
                            'text'  => '我選跑步'
                        ],
                        [
                            'label' => '瑜珈',
                            'text'  => '我選瑜珈'
                        ],
                ]
            ];
	}

	private function testConfirmTemplate()
	{
		return [
                'text' => '今天要上班嗎?',
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
	}

	private function testCarouselTemplate()
	{
		return [
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
	}

	private function testImageCarouselTemplate()
	{
		return [
		            [
		                'imageUrl' => 'https://static.juksy.com/files/articles/78005/5adfea9b3364f.gif?m=widen&i=1000',
		                'actionBuilder' => [
		                                        'label' => 'View detail',
		                                        'uri'  => 'https://www.juksy.com/archives/78005'
		                                ],
		            ],
		            [
		                'imageUrl' => 'https://static.juksy.com/files/articles/78063/5ae695213024b.PNG?m=widen&i=1000',
		                'actionBuilder' => [
		                                        'label' => 'View detail',
		                                        'uri'  => 'https://www.juksy.com/archives/78063'
		                                ],
		            ],
		            [
		                'imageUrl' => 'https://static.juksy.com/files/articles/77987/5adeba7bf1d20.jpg?m=widen&i=1000',
		                'actionBuilder' => [
		                                        'label' => 'View detail',
		                                        'uri'  => 'https://www.juksy.com/archives/77987'
		                                ],
		            ],
		            [
		                'imageUrl' => 'https://static.juksy.com/files/articles/78002/5ae03d29b3b41.JPG?m=widen&i=1000',
		                'actionBuilder' => [
		                                        'label' => 'View detail',
		                                        'uri'  => 'https://www.juksy.com/archives/78002'
		                                ],
		            ],
		        ];
	}

}

?>