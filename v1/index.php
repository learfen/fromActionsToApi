<?php
/**
 * Las vistas tienen una esquema diferente a las acciones, cuentan con un minisistema de plantilla/componente 
 * pero es posible agregar cualquier biblioteca que requiera, estas se listan y nombran en el archivo de config.version.json.
 * 
 */
 
	import("auth");
	import("view");
	import("template");
	
	$dataDefault = [
		"dominio"=>config("url/dominio"),
		"routesUrl"=>"@routes/"
	];
	
	$file = view();
	echo managerTemplate( $file, $dataDefault );
	response($file);
	#response( managerTemplate( $file, $dataDefault ) );
