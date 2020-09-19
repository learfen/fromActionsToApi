<?php

require_once path("src/actions/defaults.php");
require_once path("src/actions/".entity()."/index.php");

$actionFile = path("src/actions/".entity()."/".actionRequest().".php");

function actionsDefaultAfter($name, $data){
	if(array_key_exists($name, $GLOBALS["actionsDefaultAfter"])){
		$data = $GLOBALS["actionsDefaultAfter"][$name]($data);
	}
	return $data;
}

// si la accion fue definida en la entidad
if(file_exists( $actionFile )){
	require_once $actionFile;
	actions( entity()."/".actionRequest() )(
		entityVal(),
		actionRequestVal()
	);	
}else{
	// si la accion fue cargada desde acciones por defecto o desde alguna accion en el index
	if(array_key_exists( entity()."/".actionRequest(), actions("*") )){
		actions( entity()."/".actionRequest() )(
		entityVal(),
		actionRequestVal()
	);		
	}else{
		error("Funcion no encontrada '".entity()."/".actionRequest()."', no es una funcion por defecto y tampoco se creo la accion en la entidad '$actionFile'");
	}
}

