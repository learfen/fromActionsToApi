<?php

function myGetType($var)
{
	if (strtotime($var)) return "date";
	if (is_array($var)) return "array";
	if (is_bool($var)) return "boolean";
	if (is_float($var)) return "float";
	if (is_int($var)) return "integer";
	if (is_null($var)) return "NULL";
	if (is_numeric($var)) return "numeric";
	if (is_object($var)) return "object";
	if (is_resource($var)) return "resource";
	if (is_string($var)) return "string";
	return "unknown type";
}

function is_date($date, $format = 'Y-m-d h:i:s'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function is_image($file){
	$typeValid = explode('/', $file['type']);
	if($typeValid[0] == 'image'){
		return ( $typeValid[1] == "jpg" || $typeValid[1] == "png" || $typeValid[1] == "jpeg" );
	}
	return false;
}