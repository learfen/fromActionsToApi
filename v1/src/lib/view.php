<?php

function view(){
	responseType('view');
	$target = $GLOBALS["viewDefault"];
	if( $_SERVER["REQUEST_URI"] != "/"){
		$target = explode("/" , $_SERVER["REQUEST_URI"])[1];
	}
	$template = config("views/$target");
	if(is_array($template["if"])){
		if(viewsAuth($template["if"][0]) == true){
			return $template["if"][1];
		}else{
			if(array_key_exists("else", $template)){
				if(strpos($template["else"], "html") > 0){
					return $template["else"];
				}
				return config("views/".$template["else"])["if"];
			}
		}
	}
	if(is_string($template["if"])){
		return $template["if"];
	}
	response("<h3>Error, vista no encontrada</h3>");
}