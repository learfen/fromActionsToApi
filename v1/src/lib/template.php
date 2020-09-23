<?php		
	function componentsList(string $ruta) : array {
		$files = [];
		if (is_dir($ruta)) {
			if ($dh = opendir($ruta)) {
				while (($file = readdir($dh)) !== false) {
					if (is_dir($ruta . $file) && $file!="." && $file!=".."){
						componentsList($ruta . $file . "/");
					}else{
						if($file!="." && $file!=".." && strpos($file , 'html') > -1){
							$files[str_replace('.html','',$file)] = file_get_contents($ruta.$file) ;
						}
					}
				}
				closedir($dh);
			}
		}
		return $files;
	}

	function componentsLoader() : array {
		$components = componentsList( config('components/path') );
		if( isMobile() ){
			$components['pubs'] = '';
		}
		return $components;
	}

	function componentsOverwrite( string $layoud , array $data ) : string {
		$data["version"] = config('url/dominio').config('versionActive');
		foreach ($data as $key => $value) {
			$value = is_array($value) ? json_encode($value) : $value;
			$layoud = str_replace('{$'.$key.'}' , $value , $layoud);
			$layoud = str_replace('<$'.$key.'>' , $value , $layoud);
		}
		return $layoud;
	}

	function managerTemplate(string $file , array $data ,  array $components=[]) : string {
		/**
		 * $file = viewName || viewName/file.html
		 */
		$filename = "index";
		$folder = $file;
		if(strpos($file, ".") > 0){
			[
				$folder,
				$filename
			] = explode("/", $file);
		}
		$filepath = path("src/views/$folder/$filename.html");
		$filepath = str_replace(".html.html",".html",$filepath);
		$body = file_get_contents( $filepath );
		while( strpos($body , "<$") > -1 || strpos($body , '{$') > -1 ){
			$body = componentsOverwrite( $body , $components);
			$body = componentsOverwrite( $body , $data);
		}
		return $body;
	}

	function managerSocial(array $description) : array{}

	function managerComponents(array $components) : string{}

	function pubs(string $list) : array {}