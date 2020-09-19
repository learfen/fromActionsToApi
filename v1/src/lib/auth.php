<?php

function auth(){
	require_once path("src/lib/php-jwt-master/src/JWT.php");
	$jwtObject = new Firebase\JWT\JWT;
	if(array_key_exists("Authorization", apache_request_headers())){
		if(sizeof(explode(" ", apache_request_headers()["Authorization"] )) > 1){
			[$type , $token ] = explode(" ", apache_request_headers()["Authorization"] );
			if($type == "Bearer"){
				try{ return $jwtObject::decode($token, $_SERVER["REMOTE_ADDR"].config("session/secret") , array('HS256'));
				}catch(Exception $e){ error($e); }
			}
		}
	}
	error("token not found");
	return false;
}

function actionsAuthDefine($name){
	// 
	if(array_key_exists($name, actionsAuth())){
		$userData = auth();
		if( !is_bool($userData) 
			&& is_object($userData)
		){
			#guardamos en una global
			authDataJWT($userData);

			if(actionsAuth()[$name] == false ){
				return true;
			}
		
			$actionsAuthArray = explode(",", actionsAuth()[$name]);
			if( is_int( array_search($userData->rol, $actionsAuthArray ) ) ) {
				return true;
			}
			return false;
		}
		if(actionsAuth()[$name] === false){
			return true;
		}
	}
	return null;
}