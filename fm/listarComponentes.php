<? 
ini_set('display_errors', 1);
require_once('config/config.php');
require_once('utilidades/utilidades.php');

if (!isset($conf)){
	$conf = include 'config/config.php';
	//TODO switch to array
	extract($conf, EXTR_OVERWRITE);
}

//listar todos los modulos disponibles

$mods = array();

$m = consulta(sprintf("SELECT DISTINCT `carpeta`,`nombre` FROM `componentes_instalados` WHERE `cloud` = %s GROUP BY `carpeta`",varSQL(__sistema)),true);

if($m==false){
	$mods = ($mods==false)?false:$mods;
}else{
	foreach($m['resultado'] as $r){
		if(file_exists(__root.'componentes/'.$r['carpeta'].'/LM.php')|file_exists(__root.__path.'componentes/'.$r['carpeta'].'/LM.php')){
			$r['ruta'] = "/LM".$r['carpeta']."/";
			$r['icon'] = '/componentes/dashboard/res/app.png';
			$mods['cuerpo'][] = $r;
		}
	}
}

echo json_encode($mods);


