<?php

namespace App\Services\Logics;

class NormalLogic{

	protected $imageList;

	public function __construct()
	{
		$this->imageList = [
			[
				'key' => '快點',
				'image' => 'https://cdn2.ettoday.net/images/2709/2709691.jpg'
			],
			[
				'key' => '蛤',
				'image' => 'https://i.imgur.com/XprlVqq.jpg'
			],
			[
				'key' => '87%',
				'image' => 'https://static.ettoday.net/images/2783/d2783755.jpg'
			],
			[
				'key' => '心痛',
				'image' => 'https://pic.pimg.tw/like9417/1504869870-1400093269_n.png?v=1504869879'
			],
			[
				'key' => '唉',
				'image' => 'https://i.imgur.com/TSsVaxn.jpg'
			],
			[
				'key' => '屁孩',
				'image' => 'https://i1.kknews.cc/SIG=3s5j7vs/153700034620r78qq6p8.jpg'
			],
			[
				'key' => '來啊1',
				'image' => 'https://briian.com/wp-content/uploads/2017/10/yinwubrtextmaker_4.png'
			],
			[
				'key' => '來啊2',
				'image' => 'https://buzzorange.com/techorange/wp-content/uploads/sites/2/2018/04/AvB3q4I.jpg'
			],
		];
	}

	public function analyticsMessage(&$reply, &$type, $message)
	{
		if(keyword_exists($message,['哈囉','你好','妳好','您好','Hello','hello','嗨'])){

			$reply = '我是R-bot，你好啊～';
			$type  = 'msg';

		}elseif(strpos($message,'-help') !== false){

			$reply = '目前功能如下：'."\n";
			$reply .= '[打招呼]'."\n";
			$reply .= '[提供外幣情報]'."\n";
			$reply .= '[講講屁話!]'."\n";

			$type  = 'msg';

		}elseif((strpos($message,'賴') !== false && strpos($message,'通用') !== false) || (strpos($message,'line') !== false && strpos($message,'通用') !== false)){

			$reply = '我也這麼覺得~!';
			$type  = 'msg';

		}else{

			$this->analyticsImage($reply, $message);
			$type  = 'img';

		}

	}

	private function analyticsImage(&$reply, $message)
	{
		foreach($this->imageList as $val){
			if(strpos($message,$val['key']) !== false){
				$reply = $val['image'];
				break;
			}
		}
	}

}

?>