<?php
/**
 * Esta estructura tiene como base conceptos como decoradores de python, implementación microservicios 
 * y programación funcional.
 * 		Si bien aqui no se implementa especificamente un microservicio, realizar una migración a estos 
 * es muy simple por la distribución de las funcionalidades por rutas y sus importaciones donde como se ve es posible 
 * importar funcionalidades de otras rutas. Pero tambien podria implementar POO sin ningun inconveniente separando 
 * cada objeto e importandolo cada vez, recordar no crear estos objetos en archivos nuevos de la carpeta src/actions,
 * ya que estos son utilizados para definir las rutas. 
 * 
 * (?) Definir acciones por ejemplo /autos/1/valuar
 * Si no existe la entidad se debera crear la carpeta en actions y dentro un archivo con el nombre valuar.php
 * de la siguiente manera 
 * src/
 * 		actions/
 * 				autos/
 * 						valuar.php
 * 		
 * 		valuar.php
 * 		Ejemplo 1: si utilizara la funcion en otras acciones es recomendable que no la cree de forma anonima 
 * 		<?php
 *	 		function valuar(){}
 *	 		action("metodo", "autos/valuar", ()=>{ return valuar( entityVal() ) }, access)
 * 		
 * 		Ejemplo 2: 
 * 		<?php
 * 			action("metodo", "autos/valuar", ()=>{ }, access)
 * 		access responde a los permisos por roles de los usuarios, para acceder a x accion puede poner 
 * 		access = "admin,user" o bien pude especificar que solo deba estar logueado sin importar el rol
 * 		access = "any" o quizas desee una ruta sin autenticacion access = false
 * 		si access no es enviado como parametro tomara el valor declarado en el archivo config.proyect.json 
 * 		(sesion/authDefault), para false no requiere un token de validación, y para true se considera "any"
 * se recomienda crear en la carpeta /api un archivo con el nombre de la entidad
 * e importar este archivo index.php
 * 
 * 	api/
 * 		autos.php
 * 		<?php
 * 			if(){
 * 				#middleware
 * 			}
 * 			require_once path('src/actions/index.php');
 * 	Este archivo podria crearse automaticamente, pero no lo hace porque se espera que se declaren los middleware por
 * entidad y que este corresponda a las acciones correspondientes a esta
 * 
 * Puede redireccionar luego de ejecutar una accion enviado un parametro "redir" en el metodo POST con el nombre de la vista
 */
require_once path("src/actions/defaults.php");
if(file_exists(path("src/actions/".entity()."/index.php"))){
	require_once path("src/actions/".entity()."/index.php");
}

$actionFile = path("src/actions/".entity()."/".actionRequest().".php");

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
		echo "existe la accion";
		actions( entity()."/".actionRequest() )(
		entityVal(),
		actionRequestVal()
	);		
	}else{
		error("Funcion no encontrada '".entity()."/".actionRequest()."', no es una funcion por defecto y tampoco se creo la accion en la entidad '$actionFile'");
	}
}

