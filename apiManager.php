<?php

function apiManager(){
	if( strpos($_SERVER["REQUEST_URI"] , '@') ){ 
		responseType("api");
		$data = explode("/" , $_SERVER["REQUEST_URI"]);
		entity( str_replace("@", "", $data[1]) );
				
		entityVal( sizeof($data) > 2 ? $data[2] : '');
		actionRequest( sizeof($data) > 3 ? $data[3] : '');
		actionRequestVal( sizeof($data) > 4 ? $data[4] : '');
		if(entity() != "routes"){
			import("actions");
		}
		if(file_exists("./api/".entity().".php")){
			require_once "./api/".entity().".php";
		}else{
			if(file_exists("./".config("versionActive")."/src/actions/".entity())){
				require_once "./".config("versionActive")."/src/actions/index.php";
			}
		}
		return true;
	}
	return false;
}