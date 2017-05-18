<? 
ini_set('display_errors', 1);
require_once('config/config.php');
require_once('utilidades/utilidades.php');

if (!isset($conf)){
	$conf = include 'config/config.php';
	//TODO switch to array
	extract($conf, EXTR_OVERWRITE);
}


$carpeta_ficheros = $config['base_upload_folder'].$_GET['current'];
$tipo = (isset($_GET['tipo'])&&$_GET['tipo']!='')?$_GET['tipo']:'';
//dump($carpeta_ficheros);
$files = scandir($carpeta_ficheros);
$res['archivos'] = array();
$res['info']     = folder_info($carpeta_ficheros);
foreach($files as $file){
	$current = $carpeta_ficheros.$file;
	if($file != "." && $file != ".." && $file != "thumbs" && !(strpos($file, '__')===0)){
		if(is_dir($current)){
			$type = 0;
			list($size,$archivos,$carpetas) = folder_info($current);
			$extension = '.';
			$dir       = '.';
			$thumb_dir = '.';
			//agregar la nueva carpeta
			$res['archivos'][] = array('nombre'=>$file,"tipo"=>$type,"tamano"=>$size,"archivos"=>$archivos,"carpetas"=>$carpetas,"extension"=>$extension,"direccion"=>$dir,"thumb"=>$thumb_dir);
		}else{
			$type = 1;
			$size = filesize($current);
			$archivos = 0; $carpetas = 0;
			$extension = substr(strrchr($current,'.'),1);
			$dir       = $config['base_url'].$config['upload_folder'].$_GET['current'].$file;
			$thumb_dir = $config['base_url'].$config['thumbs'].$_GET['current'].$file;
			if($tipo!=''&&$tipo!='file'){//aplicar el filtro si no es cualquier archivo
				if($tipo=='image'&&in_array($extension, $ext_img)){
					$res['archivos'][] = array('nombre'=>$file,"tipo"=>$type,"tamano"=>$size,"archivos"=>$archivos,"carpetas"=>$carpetas,"extension"=>$extension,"direccion"=>$dir,"thumb"=>$thumb_dir);//agregar la imagen
				}elseif($tipo=='media'&&in_array($extension, $ext_video)){
					$res['archivos'][] = array('nombre'=>$file,"tipo"=>$type,"tamano"=>$size,"archivos"=>$archivos,"carpetas"=>$carpetas,"extension"=>$extension,"direccion"=>$dir,"thumb"=>$thumb_dir);//agregar el video
				}else{
					continue;
				}
			}else{
				$res['archivos'][] = array('nombre'=>$file,"tipo"=>$type,"tamano"=>$size,"archivos"=>$archivos,"carpetas"=>$carpetas,"extension"=>$extension,"direccion"=>$dir,"thumb"=>$thumb_dir);
			}
		}
	}
}
echo json_encode($res);