<?php
	
	import("template");
	
	$dataDefault = [
		"dominio"=>config("url/dominio"),
		"routesUrl"=>"@routes/"
	];
	
	response( managerView( view(),	$dataDefault ) );
	