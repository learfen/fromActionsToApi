<?php

function setUserImage(){
	if( array_key_exists( "name" , $_FILES["user_image"] ) ){
		import('crudFile');
		$item["icon"] = crudFile()->setImage( $_FILES['user_image'] , "users" );
		if($item["icon"] == "false"){
			error("action > save > Error upload");
		};
	}
}

action("post", "users/save", function(){
	require_once path("src/actions/users/get.php");
	[
		'nick'=>$nick,
		'password'=>$password
	] = post() ;
	$userSaved = getUser($nick);
	if(is_array($userSaved)){
		$userSaved = (sizeof($userSaved) == 0 );
	}
	if( $userSaved ){
		if(!array_key_exists("emulate", post() )){
			response(["dataSet" => array("nick"=>$nick)]);
			return ActionsInCrud::set([
				"id" => "new",
				"created" => date("Y-m-d h:s"),
				"nick" => $nick,
				"email" => "",
				"password" => password_hash($password, PASSWORD_DEFAULT),
				"role"=>2,
				"state"=>true
			]);
		}
		return true;
	}
	error("Exist nick user");
	return false;
}); # null for not requerid auth

if(entityVal() == "new"){
	actionRequest("save");
}