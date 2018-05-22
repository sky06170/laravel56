<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class JuksyService{

	public function __construct(){}

	public function getBannerList()
	{
		$method = 'get';
        $uri = 'https://www.juksy.com/';
        $formParams = [];
        $response = $this->sendRequest($method,$uri,$formParams);
        $body = $response->getBody();
        $contents = $body->getContents();
        $list = $this->analyticBannerHTML($contents);
        return $list;
	}

	private function analyticBannerHTML($contents)
	{
		$crawler = new Crawler($contents);

        $datas = [];

        $crawler->filterXPath('//section[@class="indexSlider owl-carousel owl-theme"]/div[@class="sliderItem"]')->each(function(Crawler $node, $i) use(&$datas) {
        	$datas[$i]['articleUrl'] = $node->filterXPath('//div[@class="banner"]/a')->attr('href');
        	$datas[$i]['imageUrl']   = $node->filterXPath('//div[@class="banner"]/a/picture/source/img')->attr('src');
        	$datas[$i]['title'] = $node->filterXPath('//div[@class="banner"]/a/div/h3')->text();
        });

        return $datas;
	}

	private function sendRequest($method,$uri,$formParams)
    {
        $client = new Client();
        return $client->request($method,$uri,[
            'form_params' => $formParams
        ]);
    }

}

?>