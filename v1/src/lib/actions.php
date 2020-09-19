<?php

import("auth");
import("actionsInCrud");

$GLOBALS["actions"] = array(
	"listActions"=>function(){
		responseData( array_keys(actions()) );
	}
);


function action($methods, $name, $fn, $auth=null){
	$method = $_SERVER["REQUEST_METHOD"];
	$methods=explode(",",strtoupper($methods));
	if($methods == ["*"] || 
		is_int(array_search($method,$methods))
	){
		if($auth === null){
			$auth=config("session/authDefault");
		}else{
			if($auth === false){
				actionsAuth($name,false);
			}else{
				actionsAuth($name,$auth);
			}
		}
		$GLOBALS["actions"][$name] = $fn;
	}
}

function actions($name=""){
	$auth = actionsAuthDefine($name);
	if( $auth === null || $auth === true ){
		if( $name == "*" ){
			return $GLOBALS["actions"];
		}
		if(array_key_exists($name, $GLOBALS["actions"])){
			return $GLOBALS["actions"][$name];
		}
	}
	return ( function(){
		error("access invalid");
	} );
}


