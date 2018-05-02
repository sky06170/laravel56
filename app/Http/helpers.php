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

?>