<?
function categorias(){
	require('htma/categorias.php');
}

function categoria(){
	require('htma/categoria.php');
}

function agregarCategoriaHija(){
	$sql = sprintf("INSERT INTO `categorias` (`id_padre`,`nombre`,`cloud`,`nivel`,`componente`) VALUES(%s,%s,%s,%s,%s)",varSQL($_POST['padre']),varSQL($_POST['nombre']),varSQL(__sistema),varSQL(($_POST['nivel']+1)),varSQL($_POST['componente']));
	dump($sql);
	consulta($sql);
}

function editarCategoriaPrincipal(){
	$sql = sprintf("UPDATE `categorias` SET `nombre` = %s WHERE `id` = %s",varSQL($_POST['nombre']),varSQL($_POST['id']));
	consulta($sql);
}

function borrarCategoria($cID){
	$sql = sprintf("SELECT * FROM `categorias` WHERE `id_padre` = %s",varSQL($cID));
	$c   = consulta($sql,true);
	if($c!=false){
		foreach($c['resultado'] as $i){
			borrarCategoria($i['id']);
		}
	}
	$con = sprintf("DELETE FROM `categorias` WHERE `id` = %s",varSQL($cID));
	echo $con."/n";
	consulta($con);
}

function eliminarCategoriaPrincipal(){
	borrarCategoria($_POST['id']);
}

