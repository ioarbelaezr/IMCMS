<?
function pg(){
	require('htma/listado.php');
}

function add(){
	$sql = sprintf("INSERT INTO `contenidos` (`titulo`,`cloud`) VALUES (%s,%s)",
		varSQL($_POST['titulo']),
		varSQL(__sistema));
	$res = consulta($sql);
	header('Location: '.__url_real.'adm/pg/edt/'.$res['IDI'].'/');
}
function edt(){
	require('htma/editar.php');
}

function opciones(){
	require('htma/opciones.php');
}

function eliminar(){
	$c = sprintf("DELETE FROM `contenidos` WHERE `id` = %s",varSQL($_POST['id']));
	consulta($c);
}