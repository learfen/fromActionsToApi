<?php 
    

    function put($data=null){
      if($data != null){
        $GLOBALS["PUT"] = $data;
      }
      return $GLOBALS["PUT"];
    }

    function patch($data=null){
      if($data != null){
        $GLOBALS["PATCH"] = $data;
      }
      return $GLOBALS["PATCH"];
    }

    $parameters = array();
    
    // first pull the GET vars
    if(isset($_SERVER['QUERY_STRING'])){
      parse_str($_SERVER['QUERY_STRING'], $parameters);
    }

    // pull POST/PUT bodies
    $body = file_get_contents("php://input");
    $content_type = false;
    if(isset($_SERVER['CONTENT_TYPE'])){
      $content_type = $_SERVER['CONTENT_TYPE'];
    }
    switch ($content_type) {
      case "application/json":
        $body_params = json_decode($body);
        if($body_params){
          foreach ($body_params as $param_name => $param_value) {
            $parameters[$param_name] = $param_value;
          }
        }
        break;
      
      case "application/x-www-form-urlencoded":
        parse_str($body, $postvars);
        foreach ($postvars as $field => $value) {
          $parameters[$field] = $value;
        }
        break;

      default:
        # we could parse other supported formats here
        break;
    }


    if($_SERVER["REQUEST_METHOD"] == "PUT"){
      put($parameters);
    }
    else{
      patch($parameters);
    }