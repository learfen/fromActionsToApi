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
	// consultamos si fueron definidos los permisos para esta accion
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

function viewsAuth($query){
	// consultamos si fueron definidos los permisos para esta vista
		$userDataObject = auth();
		$userData = array();
		foreach ($userDataObject as $key => $value) {
			$userData[$key] = $value;
		}
		# convertimos la query a array para buscar el rol del usuario
		$error = function($text){ return "<h3>$text</h3>"; };
		foreach ($query as $key => $value) {
			# buscamos los roles
			if($key == "rol"){
				if(! array_key_exists("rol", $userData)){
					return false;
				}
				return viewRol( $userData["rol"] , $error , $value);
			}else{
				if($userData[$key] != $value){
					responseHTML( $error("Not autorized") );
				}
			}
		}
		return true;
}

function viewRol( $rolUser , $error , $rolesAccess){
	if($value != "any"){
		$rolesAccess = explode(",", $rolesAccess);
		if( is_int( array_search($rolUser, $rolesAccess) ) ){
			return true;
		}
		responseHTML( $error("Not autorized") );
	}
	return false;
}