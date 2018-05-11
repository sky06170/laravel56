<?php

namespace App\Services\Logics;

use App\Services\JuksyService;

class JuksyLogic{

	protected $juksyService;

	public function __construct()
	{
		$this->juksyService = new JuksyService();
	}

	public function analyticsMessage(&$reply, &$type, $message)
	{
		if(keyword_exists($message, ['juksy最新消息', 'Juksy最新消息'])){
			$type = 'carousel_image';
			$reply = $this->showJuksyBannerList();
		}
	}

	private function showJuksyBannerList()
	{
		$lists = $this->juksyService->getBannerList();

		$columns = [];

        foreach($lists as $list){
            $item = [
                'imageUrl' => str_replace('i=1920x640','i=1000x331',$list['imageUrl']),
                'actionBuilder' => [
                        'label' => 'View detail',
                        'uri' => 'https://www.juksy.com'.$list['articleUrl']
                    ]
            ];
            array_push($columns, $item);
        }

        return $columns;
	}

}

?>