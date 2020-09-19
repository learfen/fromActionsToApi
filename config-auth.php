<?php


$GLOBALS["authDataJWT"] = array();
$GLOBALS["actionsAuth"] = array();
$GLOBALS["actionsDefaultAfter"] = array();

function actionDefaultAfter($key=null , $data=null){
	if($key != null){
		$GLOBALS["actionsDefaultAfter"][$key]=$data;
	}
	return $GLOBALS["actionsDefaultAfter"];
}
function authDataJWT($data=null){
	if($data != null){
		$GLOBALS["authDataJWT"] = $data;
	}
	return $GLOBALS["authDataJWT"];
}
function actionsAuth($key=null , $data=null){
	if($key != null){
		$GLOBALS["actionsAuth"][$key]=$data;
	}
	return $GLOBALS["actionsAuth"];
}
