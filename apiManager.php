<?php

function apiManager(){
	if( strpos($_SERVER["REQUEST_URI"] , '@') ){ 
		responseType("api");
		$data = explode("/" , $_SERVER["REQUEST_URI"]);
		entity( str_replace("@", "", $data[1]) );
				
		entityVal( sizeof($data) > 2 ? $data[2] : '');
		actionRequest( sizeof($data) > 3 ? $data[3] : '');
		actionRequestVal( sizeof($data) > 4 ? $data[4] : '');
		/*
		print_r([
			'entity'=>entity(),
			'entityVal'=>entityVal(),
			'actionRequest'=>actionRequest(),
			'actionRequestVal'=>actionRequestVal(),
		]);
		*/
		if(entity() != "routes"){
			import("actions");
		}
		require_once "./api/".entity().".php";
		return true;
	}
	return false;
}