<?php
// esta cosa servira en un futuro para hacer server render xD lo probe y funciona por eso lo dejo
function metadataPage($article , $articles){
	$data = array(
		"styles"=>'
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
		'
	);
	if( config("mode") == "prod" ){
		$data = array(
			"articleDisplay"=>  !($article == false) ,
			"articleTitle"=> "",
			"articleIntro"=> "",
			"articleBody"=> "",
			"articleImg"=>"", 
			"styles"=>'
				<link href="'.config('url/dominio').config('versionActive').'/src/css/bulma-0.9.0/css/bulma.min.css" rel="stylesheet">
			'
		);
		$data["ogtitle"] = "PowerNoticias";
		$data["ogurl"] = config("url/dominio");
		$data["ogdescription"] = "powernoticias, power, noticias, pronostico, portal, goya, corrientes";
		$data["ogimg"] = config("url/dominio")."vista/img/logo";

		if( $article != false ){
			$data["articleTitle"] = $article["title"]; 
			$data["articleIntro"] = $article["intro"]; 
			$data["articleBody"] = $article["body"]; 
			$data["articleImg"] = $article["articleImg"]; 
			
			$data["ogtitle"] = substr(trim(strip_tags($article["title"])), 0, 100);
			$data["ogdescription"] = substr(trim(strip_tags($article["intro"])), 0, 100);
			$data["ogurl"] = config("url/dominio")."?article=".$article["id"];
			$data["ogimg"] = $article["articleImg"];
		}
		$data["articles"] = $articles;
	}
	return $data;
}