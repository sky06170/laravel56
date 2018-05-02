<?php

namespace App\Services\Logics;

use App\Services\CurrencyConverterService;

class CurrencyLogic{

	protected $currencyService;

	public function __construct()
	{
		$this->currencyService = new CurrencyConverterService();
	}

	public function analyticsMessage(&$reply, &$type, $message)
	{
		$type = 'msg';

		if(strpos($message,'外幣種類') !== false){

			$this->getCurrencyKindList($reply, $message);

		}elseif(strpos($message,'匯率') !== false){

			$this->getExchangeRate($reply, $message);
			
		}elseif(keyword_exists($message,['圜','幣','圓','元','圜','克朗','銖','里拉','披索','盾']) && strpos($message,'換') !== false && strpos($message,'測試') === false){

			$price = preg_replace('/[^.0-9]/','',$message);

			$strings = explode('換',$message);

			$string1 = $strings[0];

			$string2 = $strings[1];

			if($price === ''){
				$reply = '請輸入金額';
			}elseif(!keyword_exists($string1,['圜','幣','圓','元','圜','克朗','銖','里拉','披索','盾'])){
				$reply = '請輸入第一項貨幣名稱';
			}elseif(!keyword_exists($string2,['圜','幣','圓','元','圜','克朗','銖','里拉','披索','盾'])){
				$reply = '請輸入第二項貨幣名稱';
			}else{
				$this->exchangeRateConversion($reply, $price, $string1, $string2);
			}
			
		}
	}

	public function getCurrencyKindList(&$reply, $message)
	{
		$lists = $this->currencyService->generateYahooList();
		foreach($lists as $key => $list){
			$reply .= $list['name'];
			if($key < count($lists) - 1){
				$reply .= '，';
			}
		}
	}

	public function getExchangeRate(&$reply, $message)
	{
		$lists = $this->currencyService->generateYahooList();
		foreach($lists as $key => $list){
			if(strpos($message,$list['name']) !== false || keyword_exists($message,$list['alias'])){
	            $reply .= '外幣名稱'." - ".$list['name']."\n";
	            $reply .= '最佳銀行'." - ".$list['bank']."\n";
	            $reply .= '即時買進'." - ".$list['immediate_buy']."\n";
	            $reply .= '即時賣出'." - ".$list['immediate_sell']."\n";
	            $reply .= '現金買進'." - ".$list['cash_buy']."\n";
	            $reply .= '現金賣出'." - ".$list['cash_sell']."\n";
	            break;
			}
		}
		if($reply == ''){
			$reply .= '請輸入正確的外幣名稱！';
		}
	}

	public function exchangeRateConversion(&$reply, $price = 0, $string1, $string2)
	{
		$list = $this->currencyService->generateYahooList();

		$resultPrice = 0;

		//台幣換X幣
		if(strpos($string1,'台幣') !== false){
			foreach($list as $item){
				if(strpos($string2,$item['name']) !== false || keyword_exists($string2,$item['alias'])){
					$currencyName = $item['name'];
					$resultPrice = floor($price / $item['cash_sell']);
					break;
				}
			}
		}

		//X幣換台幣
		elseif(strpos($string2,'台幣') !== false){
			foreach($list as $item){
				if(strpos($string1,$item['name']) !== false || keyword_exists($string1,$item['alias'])){
					$currencyName = '台幣';
					$resultPrice = floor($price * $item['cash_sell']);
					break;
				}
			}
		}

		//X1幣換X2幣 = X1幣換台幣 -> 台幣換X2幣
		else{
			foreach($list as $item){
				if(strpos($string1,$item['name']) !== false || keyword_exists($string1,$item['alias'])){
					$currencyName = '台幣';
					$price = floor($price * $item['cash_sell']);
					break;
				}
			}
			foreach($list as $item){
				if(strpos($string2,$item['name']) !== false || keyword_exists($string2,$item['alias'])){
					$currencyName = $item['name'];
					$resultPrice = floor($price / $item['cash_sell']);
					break;
				}
			}
		}

		$reply = '目前可以換$'.number_format($resultPrice).$currencyName;
	}

}

?>