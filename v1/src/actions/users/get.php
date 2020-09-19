<?php

function getUser($selectVal){
	$select = "nick='$selectVal' LIMIT 1 ";
	if( is_integer( $selectVal ) ){
		$select = "id='$selectVal' LIMIT 1 ";
	}
	return crud()->select(
			config("users/table"),
			"*",
			$select);
}

action("get", "users/get" , function(){
	return getUser( entityVal() );
});