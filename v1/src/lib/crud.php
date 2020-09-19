<?php 

function crud(){
	if( ! class_exists("Class_Crud")){
		class Class_Crud 
		{ 
			public $con; 
			private $cols; 
			public function __construct() 
			{ 
				$this->con = new mysqli(config("dbAccess/server"),config("dbAccess/user"),config("dbAccess/pass"),config("dbAccess/name"));
				if ($this->con->connect_errno) {
					error("lib:crud > Fallo al conectar a MySQL: (" . $this->con->connect_errno . ") " . $this->con->connect_error);
					response();
				}
				mysqli_set_charset($this->con, "utf8");
				$this->cols = array();
			}
			// obtenemos las columnas de la tabla que vamos a insertar o actualizar para validar los campos 
			private function fn_cols($tabla){
				$sql = "SELECT * FROM $tabla WHERE 1 LIMIT 1";
				$return = array();
				if (mysqli_multi_query($this->con, $sql)) {
					$return=array(); 
					do {
						/* almacenar primer juego de resultados */
						if ($result = mysqli_store_result($this->con)) {
							while ($row = mysqli_fetch_assoc($result)) {
								foreach ($row as $key => $value) {
									$return[$key] = myGetType($value);
								}
							}
							mysqli_free_result($result);
						}

					} while (mysqli_next_result($this->con));
				}
				return $return;
			}

			public function prepare_insert($in_tabla,$in_values){
				$values = "";
				$keys = "";
				$count = 0;

				$this->cols = $this->fn_cols($in_tabla);
				foreach ($this->cols as $key => $value) {
					// aux
					$val = "";
					$count++;
					//process
					if(array_key_exists($key, $in_values)){
						if($value == "string" || $value == "date"){
							$values .= "'".$in_values[$key]."'";
						}else{
							if($in_values[$key] == null){
								$values .= "NULL"; 
							}else{
								$values .= $in_values[$key];
							}
						}
					}else{
						if($value == "string"){
							$values .= "''"; 
						}
						if($value == "numeric"){
							$values .= "0"; 
						}
						if($value == "date"){
							$values .= "'".date('Y-m-d H:i:s')."'"; 
						}
					}
					$keys .= "`".$key."`";
					if($count<sizeof($this->cols)){
						$values.=",";
						$keys.=",";
					}
					$values = str_replace(",-,", ",'-',", $values);
					$values = str_replace(",[],", ",'[]',", $values);

				}

				$sql = "INSERT INTO ".$in_tabla." (".$keys.") VALUES (".$values.")";
				return $sql;
			}

			public function prepare_insert_ready($in_tabla,$in_values){
				$values = "";
				$keys = "";
				$count = 0;

				foreach ($in_values as $key => $value) {
					// aux
					$val = "";
					$count++;
					//process
					if(array_key_exists($key, $in_values)){
						$val = $in_values[$key]; 
					}
					$values .= "'".$val."'";
					$keys .= "`".$key."`";
					if($count<sizeof($in_values)){
						$values.=",";
						$keys.=",";
					}

				}

				$sql = "INSERT INTO ".$in_tabla." (".$keys.") VALUES (".$values.")";
				return $sql;
			}

			public function escape($data){
				if(is_array($data)){
					foreach ($data as $key => $value) {
						$data[$key] = mysqli_real_escape_string($this->con, $value); 
					}
					return $data;
				}else{
					return mysqli_real_escape_string($this->con, $data);
				}
			}

			public function save($sql,$aux=array(),$ready="#null"){
				return $this->registrar($sql, $aux , $ready);
			}

			public function registrar($sql,$aux="",$ready="#null"){
				$return = false;
				if(is_array($aux)){
					$aux = $this->escape($aux);
					if($ready=="ready"||$ready=="1"){
						$sql = $this->prepare_insert_ready($sql,$aux);
					}else{
						$sql = $this->prepare_insert($sql,$aux);
					}
				}
				echo $sql;
				if(!strpos($sql, "select") && !strpos($sql, "delete")){
					
					$return = $this->con->query($sql);
				}
				if(!$return){
					error("lib:crud > save $sql");
				}
				debug("crud-insert",">> $sql <<");
				return $return ? mysqli_insert_id($this->con) : 0;
			}

			public function select($from,$select="*",$where=""){
				return $this->buscar($from,$select,$where);
			}

			public function find($from,$select="*",$attr, $text){
				return $this->buscarPor($from,$select,$attr, $text);
			}

			public function buscarPor($from,$select="*",$attr, $text){
				$sqlText = "SELECT $select FROM $from WHERE $attr LIKE '%$text%'";
				if($this->filterForSelect($sqlText)){
					$sql = $sqlText;
					if(!strpos($sql, 'LIMIT')>-1){
						$sql .= ' LIMIT 20';
					}
					$return = array();
					debug("crud-find", ">> $sql <<");
					if ( mysqli_multi_query( $this->con, $sql ) ) {
						do {
							/* almacenar primer juego de resultados */
							if ($result = mysqli_store_result($this->con)) {
								while ($row = mysqli_fetch_assoc($result)) {
									$id = $row["id"];
									$return[$id] = $row;
								}
								mysqli_free_result($result);
							}

						} while (mysqli_next_result($this->con));
					}
					return $return;
				}else{
					error("lib:crud select word invalid");
				}
				return false;
			}

			private function filterForSelect($sql){
				$sqlControl = strtolower($sql);
				return (
					!strpos( $sqlControl, "truncate") 
					&& !strpos($sqlControl, "drop")	
					&& !strpos($sqlControl, "insert") 
					&& !strpos($sqlControl, "update")
				);
			}

			public function buscar($from,$select="*",$where=""){
				if($this->filterForSelect( "SELECT $select FROM $from WHERE $where ")){
					if($select!="*"){
						/*
						$select = "`".str_replace(",", "`,`", $select)."`";
						$select = "`".str_replace(".", "`.`", $select)."`";
						$select = str_replace(" as ", "` as `", $select);
						*/
					}
					$sql = "SELECT $select FROM $from WHERE $where ";
					if(!strpos($sql, 'LIMIT')>-1){
						$sql .= ' LIMIT 20';
					}
					$return = array();
					debug("crud-select", ">> $sql <<");
					if (mysqli_multi_query($this->con, $sql)) {
						do {
							/* almacenar primer juego de resultados */
							if ($result = mysqli_store_result($this->con)) {
								while ($row = mysqli_fetch_assoc($result)) {
									$id = $row["id"];
									$return[$id] = $row;
								}
								mysqli_free_result($result);
							}

						} while (mysqli_next_result($this->con));
					}
					return $return;
				}else{
					error("lib:crud select word invalid");
				}
				return false;
			}

			public function anything_to_utf8($var,$deep=TRUE){
				if(is_array($var)){
					foreach($var as $key => $value){
						if($deep){
							$var[$key] = anything_to_utf8($value,$deep);
						}elseif(!is_array($value) && !is_object($value) && !mb_detect_encoding($value,'utf-8',true)){
							$var[$key] = utf8_encode($var);
						}
					}
					return $var;
				}elseif(is_object($var)){
					foreach($var as $key => $value){
						if($deep){
							$var->$key = anything_to_utf8($value,$deep);
						}elseif(!is_array($value) && !is_object($value) && !mb_detect_encoding($value,'utf-8',true)){
							$var->$key = utf8_encode($var);
						}
					}
					return $var;
				}else{
					return (!mb_detect_encoding($var,'utf-8',true))?utf8_encode($var):$var;
				}
			}

			public function update($table,$values="",$condicion=""){
				return $this->editar($table,$values,$condicion);
			}
			
			public function editar($table,$values="",$condicion=""){
				$sql = $table;
				if($values!="" && $condicion!=""){
					$sql = "UPDATE $table SET $values WHERE $condicion ";
				}
				if( !strpos($sql, "insert") && !strpos($sql, "select") ){
					return (bool) $this->con->query($sql);
				}else{
					header("../");
				}
			}

			public function remove($table, $id){
				if( is_integer($id) && $id > 0 ){
					return (bool) $this->con->query( "DELETE FROM `$table` WHERE id='$id'" );
				}else{
					error("lib:crud remove error not integer");
				}
				return false;
			}

			function close(){
				if( isset($GLOBALS['crud']) ){
					$this->con->close();
				}
			}

			// combina 1 tabla con 1 lista de filas obtenidas que correspondan a table_id para no hacer multiples busquedas
			public function joinByArray($rows, $table, $add){
				$table = config($table."/table");
				$add = isset($add) ? $add : crud()->select( $table, "*", " 1 ");
				foreach ($rows as $key => $row) {
					$addList = json_decode($row[$table."_id"]);
					$new = [];
					foreach ($addList as $value) {
						if(array_key_exists($value, $add)){
							$new[$value] = $add[$value];
						}
					}
					$rows[$key][$table] = $new;
				}
				return $rows;
			}
		}
	}
	if( !isset($GLOBALS['crud']) ){
		$GLOBALS['crud'] = new Class_Crud();
	}
	return $GLOBALS['crud'];
}