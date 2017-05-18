<?
function login(){
	require("htm/login.php");
}

function logg(){
	$detalles = login_user($_POST['email'],$_POST['password']);
	echo json_encode($detalles);
}

function signup(){
	$sql = sprintf("INSERT INTO `login` (`nombres`,`apellidos`,`email`,`telefono`,`contrasena`) VALUES(%s,%s,%s,%s,%s)",
		varSQL($_POST['nombre']),
		varSQL($_POST['apellido']),
		varSQL($_POST['email']),
		varSQL($_POST['telefono']),
		varSQL(md5($_POST['password'])));
	$estado = Consulta($sql);
	if ($estado['filas_afectadas'] == -1) {
		$detalles = array("estado"=>"error","detalles"=>"exist","mensaje"=>$sql);
	}
	else{
		$m = new mailer();
		$m->mensaje('Gracias por registrarte','Gracias por registrarte en igia media mobility');
		$m->add_copia($_POST['email'],$_POST['nombre']);
		$m->enviar();
		$detalles = array("estado"=>"ok","detalles"=>"");
	}
	echo json_encode($detalles);
}

function logout(){
	session_destroy();
	setcookie("sessionHash","", time()-1);
	setcookie("sessionUser","", time()-1);
	header('Location: /');
}

function forgot(){
	echo "Olvido su contraseña";
}

?>