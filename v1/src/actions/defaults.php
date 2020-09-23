<?php

function actionsDefault($actions){
	
	import('crud');
	
	if($actions == "*"
		|| array_search("list", $actions)
	){
		action("get", entity()."/list" , function (){
			$cant = 20;
			$page = actionRequestVal() === "" ? 0 : actionRequestVal();
			$page = $page*5;
			$rows = ActionsInCrud::list(" 1 LIMIT $page,5");
			$rows = actionsDefaultAfter(entity()."/list", $rows );
			responseData( $rows );
			response(["page" => $page, "cant"=>$cant]);
		});
	}
	if($actions == "*"
		|| array_search("find", $actions)
	){
		action("get", entity()."/find" , function (){
			$rows = ActionsInCrud::find(entityVal(), actionRequestVal());
			$rows = actionsDefaultAfter(entity()."/find", $rows );
			responseData( $rows );
		});
	}
	if($actions == "*"
		|| array_search("save", $actions)
	){
		action("post",entity()."/save" , function() : int{
			post()["id"] = "new";
			$result = ActionsInCrud::set( post() );
			$result = actionsDefaultAfter(entity()."/save", $result );
			return $result;
		});
	}
	if($actions == "*"
		|| array_search("set", $actions)
	){
		action("post,put,patch", entity()."/set" , function(){
			$id=entityVal(); 
			$result = [];
			if($id == "new"){
				$result = actions("save")(post());
			}else{
				foreach (post() as $key => $value) {
					$result[$key] = ActionsInCrud::setById( (int)$id, $key, $value );
				}
				$result = actionsDefaultAfter(entity()."/set", $result );
			}
			responseData( $result );
		});
	}
	if($actions == "*" 
		|| array_search("remove", $actions)
	){
		action("delete",entity()."/remove",function($id) : bool{
			$result= ActionsInCrud::removeById( $id );
			$result = actionsDefaultAfter(entity()."/remove", $result );
			return $result;
		});
	}
	if($actions == "*" 
		|| array_search("only-set-admin", $actions)
	){
		action("*","any/only-set-admin", function($config, $data){
			foreach ( $config[entityVal()] as $key ) {
				unset( $data[$key] );
			}
			$data = actionsDefaultAfter(entity()."/only-set-admin", $data );
			return $data;
		});
	}
}

function actionsDefaultAfter($name, $data){
	if(array_key_exists($name, $GLOBALS["actionsDefaultAfter"])){
		$data = $GLOBALS["actionsDefaultAfter"][$name]($data);
	}
	return $data;
}