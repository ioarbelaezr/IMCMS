<?php
$_SESSION['configuracion']['og'] = '';
//verificar si es protocolo seguro
$SecureProtocol = (
		(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]=="on")
		|| (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"]=="https" )
		|| (isset($_SERVER["HTTP_CF_VISITOR"]) && strstr($_SERVER["HTTP_CF_VISITOR"],"https")!=false )
	)?true:false;
define('__secureprotocol', $SecureProtocol);
//----------------------------------------
$dominio = str_replace('www.', '', $_SERVER['HTTP_HOST']);
$sql = sprintf('select * from clouds where dominio = %s',
	varSQL($dominio));
$datos=consulta($sql,true);
if($datos==false||$datos==''){
	require('enconstruccion.php');
	exit;
}
define('__dominio', 'www.'.$dominio);
$datos=$datos['resultado'];

$_SESSION['SITE_CNFG'] = $datos[0];

//hacer las redirecciones del caso
$mover = false;
if($datos[0]['s']=="1" && !__secureprotocol){
	$mover = true;
	$newUrl = "https://www.".$dominio.$_SERVER["REQUEST_URI"];
}elseif($datos[0]['s']=="0" && __secureprotocol){
	$mover = true;
	$newUrl = "http://www.".$dominio.$_SERVER["REQUEST_URI"];
}

if($mover==false && strstr($_SERVER['HTTP_HOST'],"www.")==false){
	$mover = true;
	$newUrl = "http".(($datos[0]['s']==1)?"s":"")."://www.".$dominio.$_SERVER["REQUEST_URI"];
}

if($mover==true){
	header( "HTTP/1.1 301 Moved Permanently" );
	header("Location: ". $newUrl);
	exit;
}
//selecciona algunas palabras claves para el meta keywords
$a = explode(',',$datos[0]['palabras']);
shuffle($a);
$a = array_chunk($a,((count($a)>10)?10:count($a)));
$b = implode(',',$a[0]);


define('__root',$_SERVER["DOCUMENT_ROOT"].'/');
define('__path',$datos[0]['folder']);
define('__path_real',"/".$datos[0]['folder']);
define('__titulo',$datos[0]['titulo']);
$_SESSION['configuracion']['titulo'] = $datos[0]['titulo'];
define('__descripcion',$datos[0]['descripcion']);
$_SESSION['configuracion']['descripcion'] = $datos[0]['descripcion'];
define('__palabras_clave',$b);
define('__url_real', ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']))?$_SERVER['HTTP_X_FORWARDED_PROTO']:"http")."://".__dominio."/");
define('__upload_dir',__root.__path.'uploads/');
define('__sistema',$datos[0]['id']);

//obtener el listado de componentes instalados para todo el sistema
if(!isset($_SESSION['componentes'])){
	$con = sprintf("SELECT * FROM `componentes_instalados` WHERE `cloud` = %s",varSQL(__sistema));
    $r = consulta($con,true);
    $_SESSION['componentes'] = $r['resultado'];
}

//definir imagen og
if (file_exists(__path."/recursos/img/og.jpg")) {
	$og = __url_real.__path."recursos/img/og.jpg";
	define("__og_image", $og);
	$_SESSION['configuracion']['og']= __og_image;
}

/*
 *calcula las urls canonicas para los sitios
 */
function URLcanonica(){
	$canonical = __url_real;
	if(!isset($_GET['adm'])&&!isset($_GET['ajax'])){
		if(isset($_GET['modulo'])){
			$canonical .= $_GET['modulo'].'/';
		}
		if(isset($_GET['funcion'])){
			$canonical .= $_GET['funcion'].'/';
		}
		if(isset($_GET['text1'])){
			$canonical .= $_GET['text1'].'/';
		}
		if(isset($_GET['text2'])){
			$canonical .= $_GET['text2'].'/';
		}
		if(isset($_GET['text3'])){
			$canonical .= $_GET['text3'].'/';
		}
		if(isset($_GET['text4'])){
			$canonical .= $_GET['text4'].'/';
		}
	}
	return $canonical;
}

//$can = "http://www.".__dominio.'/';
define("__url_canonica", URLcanonica());




