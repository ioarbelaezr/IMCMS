<?php
/**
 * IMCMS
 * @author Ivan Orlando Arbelaez
 * @copyright 2016 Ivan orlando arbelaez
 * @license GNU GENERAL PUBLIC LICENSE
*/
//verificar la instalacion
if(!file_exists('init/config.php')){
	header('Location: /instalador/instalador.php');
	exit;
}
session_start();
ini_set('display_errors', 1);
require('autoload.php');
//verifica si memcache esta activo y hay una version en cache del sitio 
//cache::memcache();

set_time_limit(60*60*10);
ini_set('memory_limit','100M');
header('X-Powered-By: @ioarbelaezr');     
header('Content-Type: text/html; charset=utf-8');
ini_set('output_buffering', 'on');
date_default_timezone_set ("America/Bogota");
ob_start();
$_SESSION['configuracion']['bootstrap'] = true;

//carga procedimientos extras para cada sitio
if(file_exists(__path.'loader.php')){
	require(__path.'loader.php');
}
//muestra el sitemap del sitio 
if(isset($_GET['acc'])&&$_GET['acc']=='sitemap'){
	$s = new sitemap;
	$s->mostrar();
	exit();
}
//muestra el archivo robots.txt
if(isset($_GET['acc'])&&$_GET['acc']=='robots'){
	require('robots.php');
	exit();
}
//requerido para el administrador de enlaces
if(isset($_GET['LM'])){
	if(!inicie_sesion()){
		echo "No se ha iniciado la sesion en el sistema";
		exit();
	}
	LM();
	exit();
}
//muestra el cache manifest
if(isset($_GET['modulo']) && $_GET['modulo']=='manifest'){
	require('frame_manifest.php');
	exit();
}
_load();
//procedimientos adicionales para enviar la respuesta al cliente
require ('utilidades/processor.php'); 

?>