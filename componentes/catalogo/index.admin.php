<?
function catalogo(){
	require('htma/catalogo.php');
}

function crear(){
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$sql = sprintf("INSERT INTO `catalogo` (`cloud`,`titulo`) VALUES(%s,%s)",varSQL(__sistema),varSQL($_POST['titulo']));
		$i   = consulta($sql);
		header('Location: /adm/catalogo/editar/'.$i['IDI'].'/');
	}
}

function editar(){
	require('htma/editar.php');
}

function subirImagenes(){
	//__upload_dir
	if(!empty($_FILES)){
		$info = pathinfo($_FILES['file']['name']);
		$ext  = array('jpg', 'jpeg', 'png');
		if(in_array(lower($info['extension']), $ext)){
			$temp  = $_FILES['file']['tmp_name'];
			$base  = __upload_dir.'__catalogo/';
			$baseT = __upload_dir.'__catalogo/thumbs/';//thumbnail
			$name  = __sistema.uniqid('',true).'.'.$info['extension'];

			$sql   = sprintf("INSERT INTO `catalogo_imagenes` (`id_catalogo`,`imagen`) VALUES (%s,%s)",varSQL($_GET['text1']),varSQL($name));
			consulta($sql);
			
			if (!file_exists($base)) {
			    mkdir($base, 0777, true);
			}
			if (!file_exists($baseT)) {
			    mkdir($baseT, 0777, true);
			}
			$destino      = $base.$name;
			$destinoThumb = $baseT.$name;

			@move_uploaded_file($temp,$destino);
			@chmod($destino, 0755);

			crear_img($destino,$destinoThumb,300,224);
			echo json_encode($_FILES);
		}else{//todo archivo no valido
			echo json_encode(array("estado"=>"fallo","code"=>6));
		}
	}
}

function listarImagenes(){
	$sql = sprintf("SELECT * FROM `catalogo_imagenes` WHERE `id_catalogo` = %s",varSQL($_GET['text1']));
	$i   = consulta($sql,true);
	echo ($i==false)?'[]':json_encode($i['resultado']);
}

function eliminarImagen(){
	$base  = __upload_dir.'__catalogo/';
	$baseT = __upload_dir.'__catalogo/thumbs/';//thumbnail
	consulta(sprintf("DELETE FROM `catalogo_imagenes` WHERE `id` = %s",varSQL($_POST['id'])));
	if(unlink($base.$_POST['imagen'])&&unlink($baseT.$_POST['imagen'])){
		echo json_encode(array("estado"=>"ok","code"=>0));
	}else{
		echo json_encode(array("estado"=>"fallo","code"=>6));
	}
}