<?php

	// nuevo
	require_once "htaccess.php";
	require_once "./config.php";
	require_once "./apiManager.php";
	require_once "./utils/response.php";
	require_once "./utils/isType.php";
	require_once "./utils/browser.php";
	#require_once "./utils/redir-urls.php";

	require_once path("src/lib/php-jwt-master/src/JWT.php");

	if($_SERVER["REQUEST_METHOD"] == "PUT"
		|| $_SERVER["REQUEST_METHOD"] == "PATCH"
	){
		require_once "./utils/request.php";
	}

	responseType( apiManager() === true ? 'api' : 'view' );
	
	if(responseType() == 'view'){
		require_once path("./index.php");
	}
	if(function_exists("crud")){
		crud()->close();
	}
	response();
	/*
	if( ! $GLOBALS['REDIR'] && actionRequest() == "" ){
		if( isMobile() )
			require_once path("index.php");
		else
			require_once path("index.php");
		if(function_exists("crud")){
			crud()->close();
		}
	}
	*/
	