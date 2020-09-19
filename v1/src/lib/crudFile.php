<?php

class Class_CrudFile
{ 
	public $folder;
	public function __construct($in) 
	{
		$in = isset($in) ? "/$in" : "" ;
		$this->folder = path("../media".$in);
		if( !file_exists($this->folder) ){
			mkdir($this->folder, 777, true);
		}
	}
	public function set($file, $type){
		$fileNew = date_format( date_create(), "U");
		$name = $this->folder."/$type-".$fileNew;
		if (move_uploaded_file($file["tmp_name"], $name )) {
			return ["success"=>$fileNew];
		}
		error("lib:crudFile > set > Error move upload file");
		return ["error"=>$file["name"]];
	}

	public function setImage($file, $in){
		if(is_image( $file )){
			$resultUploadIcon = crudFile($in)->set($file, "image");
			if(array_key_exists("success", $resultUploadIcon)){
			 	return $resultUploadIcon["success"];
			}
		}
		return "false";
	}
}

function crudFile($in=""){
	if( !isset($GLOBALS['crudFile']) || isset($in) ){
		$GLOBALS['crudFile'] = new Class_CrudFile($in);
	}
	return $GLOBALS['crudFile'];
}
 
