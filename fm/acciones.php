<?
ini_set('display_errors', 1);
session_start();
require_once('config/config.php');
require_once('utilidades/utilidades.php');
function crearCarpeta(){
	global $config;
	$base = $config['base_upload_folder'].$_GET['current'];
	$baseT = $config['base_thumbs_folder'].$_GET['current'];
	if(isset($_POST['carpeta'])){
		$carpeta = $base.$_POST['carpeta'];
		$thumbs = $baseT.$_POST['carpeta'];
		if(mkdir($carpeta,0755)&&mkdir($thumbs,0755)){
			echo json_encode(array("estado"=>"ok","code"=>0));
		}else{
			echo json_encode(array("estado"=>"fallo","code"=>5));
		}
	}else{
		echo json_encode(array("estado"=>"fallo","code"=>4));
	}
}

function eliminar(){
	global $config;
	if(isset($_POST['tipo'])&&isset($_POST['nombre'])){
		$base = $config['base_upload_folder'].$_GET['current'];
		$elm = $base.$_POST['nombre'];
		//thumbb
		$baseT = $config['base_thumbs_folder'].$_GET['current'];
		$thumb = $baseT.$_POST['nombre'];
		switch ($_POST['tipo']){
			case 0:
				eliminarDir($elm); eliminarDir($thumb);
				if(!file_exists($elm)&&!file_exists($thumb)){
					echo json_encode(array("estado"=>"ok","code"=>0));
				}else{
					echo json_encode(array("estado"=>"fallo","code"=>6));	
				}
				break;
			case 1:
				if(unlink($elm)&&unlink($thumb)){
					echo json_encode(array("estado"=>"ok","code"=>0));
				}else{
					echo json_encode(array("estado"=>"fallo","code"=>6));
				}
				break;
			default:
				break;
		}
	}else{
		echo json_encode(array("estado"=>"fallo","code"=>4));
	}
}

function upload(){
	global $config;
	if (!isset($conf)){
		$conf = include 'config/config.php';
		//TODO switch to array
		extract($conf, EXTR_OVERWRITE);
	}
	if(!empty($_FILES)){
		$info = pathinfo($_FILES['file']['name']);
		if(in_array(lower($info['extension']), $ext)){
			$temp = $_FILES['file']['tmp_name'];
			$base = $config['base_upload_folder'].$_GET['current'];
			$baseT = $config['base_thumbs_folder'].$_GET['current'];//thumbnail
			$name = $_FILES['file']['name'];
			if(file_exists($base.$name)){//generar un nuevo archivo si este existe
				$i=1;
				while(file_exists($base.$info['filename'].'('.$i.').'.$info['extension'])){
					$i++;
				}
				$name = $info['filename'].'('.$i.').'.$info['extension'];
			}

			if (in_array(lower($info['extension']),$ext_img)) $img=TRUE;
			else $img=FALSE;

			$destino      = $base.$name;
			$destinoThumb = $baseT.$name;

			@move_uploaded_file($temp,$destino);
			@chmod($destino, 0755);

			if($img){
				crear_img($destino,$destinoThumb,122,91);
			}
			echo json_encode($_FILES);
		}else{//todo archivo no valido
			echo json_encode(array("estado"=>"fallo","code"=>6));
		}
	}
}


//
function crearArchivo(){
	global $config;
	if (!isset($conf)){
		$conf = include 'config/config.php';
		//TODO switch to array
		extract($conf, EXTR_OVERWRITE);
	}   
	$base    = $config['base_upload_folder'].$_GET['current'];
	$nombre  = $_POST['nombre'];
	$archivo = $base.$nombre.$_POST['extension'];
	//archivos repetidos
	if(file_exists($archivo)){//generar un nuevo archivo si este existe
		$i=1;
		while(file_exists($base.$nombre.'('.$i.')'.$_POST['extension'])){
			$i++;
		}
		$archivo = $base.$nombre.'('.$i.')'.$_POST['extension'];
	}

	if (@file_put_contents($archivo, $_POST['contenido']) === FALSE) {
        echo json_encode(array("estado"=>"fallo","code"=>7));
    }else{
    	chmod($archivo, 0644);
    	echo json_encode(array("estado"=>"ok"));
    }
}