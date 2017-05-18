<?
$articulos = consulta(sprintf("SELECT * FROM `contenidos` WHERE `cloud` = %s ORDER BY `id` DESC ",varSQL(__sistema)),true);
if($articulos!=false){
    $articulos = $articulos["resultado"]; 
    foreach($articulos as $articulo){
    	$titulo = urlTitulo($articulo["titulo"]);
    	sitemap::addFortmat('pg/ver/{{id}}/{{titulo}}/',array('id'=>$articulo["id"],'titulo'=>$titulo));
    }
}
