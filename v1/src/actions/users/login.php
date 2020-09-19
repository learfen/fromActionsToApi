<?php

action("post", "users/login" , function(){
	require_once path("src/actions/users/get.php");
	require_once path("src/lib/php-jwt-master/src/JWT.php");
	["password" => $password , "user"=>$user] = post();
	$userSaved = getUser($user);
	if(sizeof($userSaved) > 0){
		$userSaved = $userSaved[array_keys($userSaved)[0]];
		["password"=>$password2 , "rol"=>$rol] = $userSaved;
		if(password_verify($password, $password2))
		{

	        http_response_code(200);
	        responseData("success");
	        $jwtObject = new Firebase\JWT\JWT;
			response(["jwt"=> $jwtObject::encode( 
	        	array( # token
		            "iss" => config("url/dominio"), #issuer_claim
		            "iat" => time(), #issuedat_claim
		            "nbf" => time() + 10, #notbefore_claim
		            "expire" => time() + 3600,
		            "data" => $user,
		            "rol" => $rol
	        	),
	        	$_SERVER["REMOTE_ADDR"].config("session/secret") # secret code
	        )]);
	        //response(["email"=> $email]);
	        response(["expireAt"=> time() + 3600]);

		}else{
			error("session invalid");
		}
	}else{
		error("user not exist");
	}

} , false);