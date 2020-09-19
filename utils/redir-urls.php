<?php

$GLOBALS['REDIR'] = false;
if(array_key_exists("ssl",$_GET)){
	#header("location:");
	$GLOBALS['REDIR'] = true;
}