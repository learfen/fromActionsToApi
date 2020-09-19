<?php

$basic = '

RewriteEngine on
Header set Access-Control-Allow-Origin "*"
RewriteCond %{SERVER_PORT} 8080

RewriteBase /

<FilesMatch ".(htaccess|htpasswd|ini|phps|fla|psd|log|sh|json)$">

	Order Allow,Deny

	Deny from all

</FilesMatch>

<FilesMatch ".(flv|gif|jpg|jpeg|png|ico|swf|js|css|pdf|mp4)$">

	Header set Cache-Control "max-age=2592000"

</FilesMatch>

RewriteCond %{REQUEST_FILENAME} !-f

RewriteCond %{REQUEST_FILENAME} !-d

RewriteCond %{REQUEST_FILENAME}.php -f

RewriteRule ^inicio$ /index.php [L]

RewriteRule ^@images/(.*)$  /index.php?id=$1 [L]

RewriteRule ^@media/(.*)$  /index.php?id=$1 [L]

## URLS API
';

$directorio=opendir("./api");
//recoger datos
$datos=array();
while ($archivo = readdir($directorio)) { 
  if(($archivo != '.')&&($archivo != '..')){
     $basic .= "

     RewriteRule ^@".str_replace(".php", "", $archivo)."/(.*)$  /index.php?id=$1 [L]";
  } 
}
closedir($directorio);

file_put_contents('.htaccess', $basic);