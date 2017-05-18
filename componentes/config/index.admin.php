<?

function config(){
	require('htma/list.php');
}


function siteConfig(){
	require('htma/siteConfig.php');
}

function parked(){
	require('htma/parked.php');
}

//funciones ajax

function edtSite(){
	$sql = sprintf("UPDATE `clouds` SET `nombre` = %s, `dominio` = %s WHERE `id` = %s",varSQL($_POST['nombre']),varSQL($_POST['dominio']),varSQL($_POST['id']));
	consulta($sql);
}

function instalarSitio(){
	$base      = $_SERVER["DOCUMENT_ROOT"];
	$baseSitio = $base.'/sites/'.$_POST['dominio'].'/';
	$baseArchivos = $base.'/componentes/config/archivos_base_cloud/';
	//copiar el directorio base para el sitio
	copiar($baseArchivos,$baseSitio);
	//registrar el cloud en la base de datos
	$folder = "sites/".$_POST['dominio']."/";
	$sql = sprintf("INSERT INTO `clouds` (`nombre`,`dominio`,`folder`) VALUES(%s,%s,%s)",varSQL($_POST['nombre']),varSQL($_POST['dominio']),varSQL($folder));
	$s = consulta($sql);
	//crearle el perfil de usuario de administrador
	$sql = sprintf("INSERT INTO `usuarios_perfiles` (`nombre`,`cloud`,`p`) VALUES('Administradores',%s,'1')",varSQL($s['IDI']));
	$p = consulta($sql);
	//crearle el primer usuario 
	$sql = sprintf("INSERT INTO `login` (`email`,`contrasena`,`cloud`,`perfil`) VALUES('imcms',%s,%s,%s)",varSQL(md5('imcms')),varSQL($s['IDI']),varSQL($p['IDI']));
	consulta($sql);
	echo json_encode($s);
}

function eliminarCloud(){
	ini_set('display_errors', 1);
	$sql = sprintf("DELETE FROM `clouds` WHERE `id` = %s",varSQL($_POST['id']));
	consulta($sql);
	eliminarDirectorio($_SERVER["DOCUMENT_ROOT"].$_POST['folder']);
}