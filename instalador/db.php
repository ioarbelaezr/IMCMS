<?
	ini_set('DISPLAY_ERRORS', 1);
	require('../utilidades/funciones.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Bienvenido al instalador de IMCMS</title>
</head>
<body>
	<div class="contenedor">
		<div class="logo">
			<h2>Información sobre la base de datos.</h2>
		</div>
		<div class="contenido">
			<div>
				<?
					if($_SERVER['REQUEST_METHOD']=='POST'){
							$q = sprintf("SELECT COUNT(SCHEMA_NAME) AS e FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = %s",varSQL($_POST['baseDatos']));
						$a = array(
							"servidor"=>$_POST['baseHost'],
							"usuario"=>$_POST['baseUsuario'],
							"contrasena"=>$_POST['baseContrasena'],
							"base"=>false
							);
						$res = consulta($q,true,$a);
						if($res!=false){
							if($res['resultado'][0]['e']==0){
								//$c = sprintf("CREATE DATABASE %s",$_POST['baseDatos']);
								//$res = consulta($c,false,$a);
							}else{
								//$res = consulta(sprintf("DROP DATABASE %s",$_POST['baseDatos']),false,$a);
								//$res = consulta(sprintf("CREATE DATABASE %s",$_POST['baseDatos']),false,$a);
							}
							//crear el archivo de configuracion
							ini_set('DISPLAY_ERRORS', 1);
							$nuevoarchivo = fopen('../init/config.php', "w+");
							$contenido    = "<?\n";
							$contenido   .= "define('__servidor','".$_POST['baseHost']."');\n";
							$contenido   .= "define('__usuario','".$_POST['baseUsuario']."');\n";
							$contenido   .= "define('__contrasena','".$_POST['baseContrasena']."');\n";
							$contenido   .= "define('__base','".$_POST['baseDatos']."');";
							fwrite($nuevoarchivo,$contenido);
							fclose($nuevoarchivo);
						}else{?>
							<div style="color:red;" class="text-center">No se ha podido establecer conexión con la base de datos!</div>	
						<?}
					}
				?>
				<form id="datos" action="" method="post">
					<div class="row">
						<div class="col-xs-4"><b>Nombre de la base de datos</b></div>
						<div class="col-xs-4"><input required="true" type="text" name="baseDatos"></div>
						<div class="col-xs-4">Nombre de la base de datos que desea utilizar para la instalación</div>
					</div>
					<div class="row">
						<div class="col-xs-4"><b>Nombre de usuario</b></div>
						<div class="col-xs-4"><input required="true" type="text" name="baseUsuario"></div>
						<div class="col-xs-4">El nombre de usuario de la base de datos</div>
					</div>
					<div class="row">
						<div class="col-xs-4"><b>Contraseña</b></div>
						<div class="col-xs-4"><input required="true" type="text" name="baseContrasena"></div>
						<div class="col-xs-4">La contraseña de la base de datos</div>
					</div>
					<div class="row">
						<div class="col-xs-4"><b>Host de la base de datos</b></div>
						<div class="col-xs-4"><input required="true" type="text" name="baseHost"></div>
						<div class="col-xs-4">Host donde se encuentra ubicada la base de datos</div>
					</div>
				</form>
			</div>
		</div>
		<div class="botones">
				<a class="right" href="javascript:document.getElementById('datos').submit()">Siguiente</a>
		</div>
	</div>

	<style>
		body{background-color: #E2EEF4 !important;;font-family: sans-serif;}
		.contenedor{max-width: 700px;width: 100%;background-color: white;min-height: 400px;position: absolute;left: 50%;top: 50%;transform: translateX(-50%) translateY(-50%);padding: 20px;}
		.logo{text-align: center;}
		.botones{position: absolute;left: 0px;bottom: 0px;height: 40px;width: 100%;}
		.botones a.right{background-color: #E2EEF4;color: #2EA3FF;font-size: 19px;text-decoration: none;float: right;margin: 4px;width: 50%;max-width: 200px;text-align: center;height: 32px;line-height: 32px;}
		.contenido .row, .contenido .row div{height: 80px;}
		.contenido .row div{display: table-cell;vertical-align: middle;}
	</style>
	<link rel="stylesheet" href="/frontend/bootstrap/css/bootstrap.min.css">
</body>
</html>