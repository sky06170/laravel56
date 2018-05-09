<?php

if(!function_exists('keyword_exists')){

	function keyword_exists($string,$list){
		foreach($list as $item){
			if(strpos($string,$item) !== false){
				return true;
			}
		}
		return false;
	}

}

if(!function_exists('array_get')){
	function array_get($arr, $key, $default = ''){
		if(array_key_exists($key, $arr)){
			return $arr[$key];
		}
		return $default;
	}
}

?>