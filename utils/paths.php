<?php
function pathBase(string $path) : string{
	return str_replace('v$', config('versionActive') , $path);
}

function path(string $path) : string{
	return pathBase("./v$/$path");
}

function paths(array $pathArray) : array{
	foreach ($pathArray as $key => $value) {
		$pathArray[$key] = path($value);
	}
	return $pathArray;
}

function import(string $libs) : void {
	$libArray = explode(',' , $libs);
	foreach ($libArray as $key => $file) {
		require_once path('src/lib/'.trim($file).'.php');		
	}
}

function alias($str){
	return str_replace_vars( mb_convert_case($str, MB_CASE_LOWER) , [
		'á'=>'a',
		'é'=>'e',
		'í'=>'i',
		'ó'=>'o',
		'ú'=>'u',
		' '=>'-'
	]);
}