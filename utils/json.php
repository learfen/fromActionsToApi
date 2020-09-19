<?php
function exploreJSON($url , $json){
	$array = $json;
	$url = explode("/" , $url);
	if(array_key_exists($url[0] , $array)){
		foreach ($url as $value) {
			$array = $array[$value];
		}
		return  $array;
	}
	return 'undefined';
}


function print_json(){
	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Max-Age: 3628800');
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
}


function str_replace_vars($str , $arrayData){
	foreach ($arrayData as $key => $value) {
		$str = str_replace($key, $value, $str);
	}
	return $str;
}
