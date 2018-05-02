<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CurrencyConverterService{

	public function __construct(){}

	public function generateYahooList()
    {
        $method = 'get';
        $uri = 'https://tw.money.yahoo.com/currency-converter';
        $formParams = [];
        $response = $this->sendRequest($method,$uri,$formParams);
        $body = $response->getBody();
        $contents = $body->getContents();
        $list = $this->analyticHtml($contents);
        return $list;
    }

    private function analyticHtml($contents)
    {
        $crawler = new Crawler($contents);

        $datas = [];

        $crawler->filterXPath('//table[@class="W-100 simple"]/tbody/tr')->each(function(Crawler $node, $i) use(&$datas) {
            $datas[$i]['name'] = $node->filterXPath('//td[1]/div')->text();
            $datas[$i]['alias'] = $this->getAlias($datas[$i]['name']);
            $datas[$i]['bank'] = $node->filterXPath('//td[2]/div/a')->text();
            $datas[$i]['immediate_buy'] = $node->filterXPath('//td[3]')->text();
            $datas[$i]['immediate_sell'] = $node->filterXPath('//td[4]')->text();
            $datas[$i]['cash_buy'] = $node->filterXPath('//td[5]')->text();
            $datas[$i]['cash_sell'] = $node->filterXPath('//td[6]')->text();
        });

        return $datas;
    }

    private function getAlias($name)
    {
        switch($name){
            case "美元":
                $alias = ['美圓','美幣'];
                break;
            case "澳幣":
                $alias = ['澳圓','澳元'];
                break;
            case "加拿大幣":
                $alias = ['加拿大圓','加拿大元'];
                break;
            case "港幣":
                $alias = ['港圓','港元'];
                break;
            case "英鎊":
                $alias = ['英圓','英元','英幣','英國幣'];
                break;
            case "瑞士法郎":
                $alias = ['瑞士圓','瑞士元','瑞士幣'];
                break;
            case "日圓":
                $alias = ['日元','日幣'];
                break;
            case "歐元":
                $alias = ['歐圓','歐幣'];
                break;
            case "紐西蘭幣":
                $alias = ['紐西蘭圓','紐西蘭元'];
                break;
            case "新加坡幣":
                $alias = ['新加坡圓','新加坡元'];
                break;
            case "南非幣":
                $alias = ['南非圓','南非元'];
                break;
            case "瑞典克朗":
                $alias = ['瑞典圓','瑞典元','瑞典幣'];
                break;
            case "泰銖":
                $alias = ['泰圓','泰元','泰幣','泰國幣'];
                break;
            case "人民幣":
                $alias = [];
                break;
            case "印度幣":
                $alias = ['印度圓','印度元','印幣'];
                break;
            case "丹麥幣":
                $alias = ['丹麥圓','丹麥元'];
                break;
            case "土耳其里拉":
                $alias = ['土耳其圓','土耳其元','土耳其幣'];
                break;
            case "墨西哥披索":
                $alias = ['墨西哥圓','墨西哥元','墨西哥幣'];
                break;
            case "越南幣":
                $alias = ['越南圓','越南元','越幣'];
                break;
            case "菲律賓披索":
                $alias = ['菲律賓圓','菲律賓元','菲律賓幣','菲幣'];
                break;
            case "馬來西亞幣":
                $alias = ['馬來西亞圓','馬來西亞元','馬幣','馬元'];
                break;
            case "韓圜":
                $alias = ['韓圓','韓幣','韓元'];
                break;
            case "印尼盾":
                $alias = ['印尼圓','印尼元','印尼幣'];
                break;
            default:
                $alias = [];
                break;
        }
        return $alias;
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