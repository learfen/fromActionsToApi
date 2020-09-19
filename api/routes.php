<?php

function action($methods, $name, $fn, $auth=null){
	// creamos rutas
	$methods = explode(",", $methods);
	foreach ($methods as $method) {
		[$is_entity , $is_action] = explode("/", $name);
		$is_entity = "<hr><b style='color:blue;text-transform:uppercase'>$is_entity</b>";
		if(!array_key_exists($is_entity, $GLOBALS["routes"] )){
			$GLOBALS["routes"][$is_entity] = array();
		}
		if(!array_key_exists($is_action, $GLOBALS["routes"][$is_entity] )){
			$GLOBALS["routes"][$is_entity][$is_action] = array();
		}
		
		$authDefault = $auth !== false ? "[require token] rol: <b>".str_replace(",", "|", $auth ) . "</b>" : "[require token] rol: <b style='color:#191'>any</b>";
		$authPrint = $auth === null ? "<span style='color:#f33'> NOT token </span>" : $authDefault;

		if( !is_int(array_search($method.":".$authPrint, $GLOBALS["routes"][$is_entity][$is_action]) ) ){
			array_push(
				$GLOBALS["routes"][$is_entity][$is_action],
				"<span style='min-width:6rem;display:inline-block;text-align:right'>".strtoupper($method)."</span>:".$authPrint
			);
		}
	}
		
}

require_once path("src/actions/defaults.php");

$actionsFolder=opendir(path("src/actions/"));
while ($entityName = readdir($actionsFolder)) { 
	if(
	  	$entityName != '.'
	  	&& $entityName != '..'
	  	&& $entityName != 'defaults.php'
	  	&& $entityName != 'index.php'
	){
		if(file_exists(path("src/actions/$entityName/"))){
			$entity=opendir(path("src/actions/$entityName/"));
			$datos=array();
			entity($entityName);
			while ($entityAction = readdir($entity)) { 
				if(
				  	$entityAction != '.'
				  	&& $entityAction != '..'
				  ){
				  	if(file_exists(path("src/actions/$entityName/$entityAction"))){
						require_once path("src/actions/$entityName/$entityAction");
					}
				}
			}
			closedir($entity);
		}
	}
}
closedir($actionsFolder);

responseData($GLOBALS["routes"]);