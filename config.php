<?php


	header("Access-Control-Allow-Origin: * ");
	header("Access-Control-Allow-Methods: * ");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	$GLOBALS = [
		"routes" => array(),
		# define debug comportamiento
		"mode" => array_key_exists('dev' , $_GET) ? 'dev' : 'prod',
		# url request data @entity/entityVal/action/actionRequestVal
		"fromURL" => [
			"entity"=>"",
			"id"=>"",
			"actionRequest"=>"",
			"actionRequestVal"=>""
		],
		# file config.proyect.json
		"CONFIG" => json_decode( file_get_contents("./config.proyect.json") , true),
		# DEBUG
		"debug" => [
			"crud-insert" => !true,
			"crud-select" => !true,
			"crud-find" => !true
		],
		"error" => [],
		"response" => [],
		"responseType" => "view",
		"responseHTML"=> "",
		"viewDefault"=>"default"
	];
	
    function post(){ return $_POST; }
    function get(){ return $_GET; }

	require_once "./utils/json.php";
	require_once "./utils/paths.php";
	require_once "./config-auth.php";
	
	$configVersion = json_decode( file_get_contents($GLOBALS["CONFIG"]["versionActive"]."/config.version.json") , true) ;
	foreach ($configVersion as $keyConfig => $valueConfig) {
		$GLOBALS["CONFIG"][$keyConfig] = $valueConfig;
	}

	
	function config(string $url){
		$urlArray = explode('/' , $url);
		if( $urlArray[sizeof($urlArray) - 1] == 'path' )
			return path( exploreJSON( $url , $GLOBALS["CONFIG"] ) );
		return exploreJSON( $url, $GLOBALS["CONFIG"] );
	}

	function error($data=""){
		if($data != ""){
			array_push( $GLOBALS["error"], $data);
		}
		return $GLOBALS["error"];
	}

	function responseType($data=""){
		if($data == "")
			return $GLOBALS["responseType"];

		$GLOBALS["responseType"] = $data;
		$headers = [
			'view' => function(){ header ('Content-type: text/html; charset=utf-8'); },
			'api' => function(){ print_json(); }
		];
		$headers[$data]();
	}

	function responseHTML( $html ){
		responseType("view");
		echo $html;
	}

	function entity($data=""){
		if($data != "")
			$GLOBALS["fromURL"]["entity"] = $data;
		
		return $GLOBALS["fromURL"]["entity"];
	}

	function entityVal($data=""){
		if($data != ""){
			$GLOBALS["fromURL"]["id"] = $data;
			$GLOBALS["fromURL"]["entityVal"] = $data;
			$_GET[entity()] = $data;
		}
		return $GLOBALS["fromURL"]["id"];
	}

	function actionRequest($val=""){
		if($val != ""){
			$GLOBALS["fromURL"]["actionRequest"] = $val;
		}
		return $GLOBALS["fromURL"]["actionRequest"];
	}

	function actionRequestVal($val=""){
		if($val != ""){
			$GLOBALS["fromURL"]["actionRequestVal"] = $val;
		}
		return $GLOBALS["fromURL"]["actionRequestVal"];
	}


	function debug($key, $data=null){
		if($GLOBALS["debug"][$key] == true){
			echo $data;
		}
	}