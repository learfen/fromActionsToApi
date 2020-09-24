<?php

function response($data=false){
		if($data != false){
			if(is_array($data)){
				foreach ($data as $key => $value) {
					$GLOBALS["response"][$key] = $value;
				}
			}else{
				$GLOBALS["response"] = $data;
			}
		}else{
			if( is_array($GLOBALS["response"]) ){
				if(!array_key_exists("data", $GLOBALS["response"])){
					$GLOBALS["response"]["data"] = [];
				}else{
					http_response_code(200);
				}
				$GLOBALS["response"]["error"] = $GLOBALS["error"];
				foreach ($GLOBALS["error"] as $error) {
					if($error == "Sesion invalid")
						http_response_code(400);
				}
				# redireccionamos si recibimos en post el nombre de una vista
				if(array_key_exists("redir", post()) && sizeof($GLOBALS["error"]) == 0){
					header("location: ".config("url/dominio").post()["redir"]);
				}else{
					print_r( json_encode( $GLOBALS["response"] , JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
				}
			}else{
				# redireccionamos si recibimos en post el nombre de una vista
				if(array_key_exists("redir", post())){
					header("location: ".config("url/dominio").post()["redir"]);
				}else{
					echo $GLOBALS["response"];
				}
			}
			if(function_exists("crud")){
				crud()->close();
			}
			die();
		}
}

function responseData($data){
	response(array("data"=>$data));
}

