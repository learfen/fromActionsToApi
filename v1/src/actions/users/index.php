<?php

// llamamos a todas las funciones por defecto 
if(entityVal() == "new" && array_key_exists("password", $_POST)){
	$_POST["password"] == password_hash($_POST["password"], PASSWORD_BCRYPT);
}

actionsDefault("*");

