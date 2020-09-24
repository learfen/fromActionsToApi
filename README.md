# fromActionsToApi-php

Para ver las rutas disponibles de la api dominio/@routes

Para abrir una vista dominio/nombre-vista, notese que dice nombre de vista, y en lugar de nombre de archivo, es porque las vistas deben tener un nombre al ser declaradas, ya que el sistema cuenta con un pequeño motor de plantillas, que por el momento esta incompleta la parte de componentes pero que sera agregado, para implementar de forma simple los metas y utilizar frameworks como react, vue, etc sin los inconvenientes que estos tienen normalmente en el SEO.
  + method get: dominio/login

Para una accion de una entidad, dominio/@entidad/selector/accion, @ define que es una acción y no una vista
  + method delete: dominio/@books/2/remove
  + method post: dominio/@books/new/set -> esta funcion por defecto le permitira guardar en la base de datos si esta bien configurado el archivo proyect.config.json.

Para una accion con parametro de una entidad, dominio/@entidad/selector/accion/parametro-de-accion
  + dominio/@books/20/page/2 # esta seria la declaración adecuada, cantidad 20 libros paginación 2, la funcion de paginación no esta disponible por defecto 


# Metodos y funciones por defecto
  Cada metodo cuenta con su funcion para obtener los parametros, asi como cada sección de la URL
  + post()-put()-patch()
  + /@entity/entityVal/actionRequest/actionRequestVal [entity()-entityVal()-actionReques()-actionRequestVal()]
  Para llamar a las acciones por defecto puede hacerlo con actionsDefault("*") siendo * todas o bien listandolas actionsDefault("set,get,remove");
  El sistema cuenta con algunas funciones por defecto, referidas a cada metodo, 
  + Función set() para los metodos post, put, patch, internamente cuando recibe como selector new el metodo post llama a la funcion save(), estas funciones pueden ser sobre escritas y de hecho lo son, vease el ejemplo de users.
  
  + El metodo post como particularidad puede recibir un atributo "redir" con el nombre de una vista, si la acción se ejecuta correctamente redireccionará a esta vista
  
  + El metodo delete en la funcion por defecto remove(), @entidad/id/remove, se espera que la tabla tenga el mismo nombre de la entidad o que se configure el borrado por llave foranea en la db para borrar entidades formadas por multitablas.
  
  + El metodo get tiene 2 funciones por defecto, list y get, @entidad/all/list para ver todos los items de la tabla, @entidad/id/get, para obtener una fila de la tabla con el mismo nombre de entidad

# Definición de una acción
  + action(method, urlName, function, rolAccess)
  + action(
  
    "get,post", # puede lanzarse una acción con mas de un metódo
    
    "books/list", # aunque las rutas se crean automaticamente debe indicarse el nombre, para importar solo 1 copia de cada acción```
    
    function(){
    
      if(sizeof(post()) > 0){
      
          responseData(array("Hola", "Adios"));
          
          response(); // response realiza un clousure con die(); result code {data:{0:"Hola",1:"Adios"}}
          
          // podria usar error("En caso de existir algun error"); response();
          
     }
     
  }, "root,user") # los roles deben ser declarados segun como se declaren en la db y como se envien estos en el jwt al realizar el login, cuando no se corresponda 
  
  ``` Puede usar como rol la palabra any, que indica que solo debe tener iniciada una sesion en el jwt, en caso de no declarar roles, el valor por defecto se encuentra en el archivo config.proyect.json como session/authDefault con false no requiere una sesión para ejecutar esa acción```


# Sobre config()

Puede acceder a los valores de ambas configuración(proyect y version) con la funcion config(), para acceder a diferentes niveles use config("dbAccess/name") por ejemplo, dbAccess esta en la raiz y name es el hijo.


# Recomendaciónes antes de iniciar

+ Revisar el archivo de configuración que coincida con el servidor que se este levantando, tanto del dominio como del puerto, la conexion a la base de datos, etc

+ El crud tiene una particularidad, debe existir al menos 1 registro para que este pueda manipular una tabla, por ello se recomienda que se cree un registro al crear la tabla, en versiones proximas el mismo sistema creara las tablas basado en las declaraciones de los requisitos declarados en un archivo en cada entidad.

+ Sobre el caché aún no esta en funcionamiento, pero el sistema de integración de un array a una entidad especifica como tabla si, por lo que en una versión alpha proxima se implementará el caché.

+ Este software tendra una segunda variante donde en lugar de ejecutar una accion levantara cada entidad como un microservicio por separado
