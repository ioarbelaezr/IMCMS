<?php
require('utilidades/funciones.php');     //funciones utiles del sistema
require('init/config.php');  			//Carga de configuraciones
require('init/core.php');  			//Carga de configuraciones
//las classes se cargan automaticamente al ser llamadas
function __autoload($nombre){
	require('utilidades/classes/'.$nombre.'.php');
}  			