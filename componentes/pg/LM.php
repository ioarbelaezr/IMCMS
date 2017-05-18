<?
$res['cabecera'] = array(array("ruta"=>__url_real.'LMpg/categorias/',"titulo"=>"Categorias de paginas"),array("ruta"=>__url_real.'LMpg/','titulo'=>"Listado de paginas"));
function pg($res){
	$c = consulta(sprintf("SELECT `id`,`titulo` FROM `contenidos` WHERE `cloud` = %s",varSQL(__sistema)),true);

	if($c!=false){
		foreach($c['resultado'] as $p){
			$r = __url_real."pg/ver/".$p['id']."/".urlTitulo($p["titulo"])."/";
			$res['cuerpo'][] = array('id'=>$p['id'],'titulo'=>$p['titulo'],'ruta'=>$r);
		}
	}
	return $res;
}

function categorias($res){
	$res['cuerpo'][] = array('ruta'=>__url_real.'LMpg/listarCategoria/0/','titulo'=>'Paginas sin categoria');
	$c = consulta(sprintf("SELECT `nombre`,`id` FROM `categorias` WHERE `id` IN(SELECT DISTINCT `categoria` FROM `contenidos` WHERE `cloud` = %s)",varSQL(__sistema)),true);
	if($c!=false){
		foreach($c['resultado'] as $cat){
			$res['cuerpo'][] = array('titulo'=>$cat['nombre'],'ruta'=>__url_real.'LMpg/listarCategoria/'.$cat['id'].'/');
		}
	}
	return $res;
}

function listarCategoria($res){
	$c = consulta(sprintf("SELECT `id`,`titulo` FROM `contenidos` WHERE `cloud` = %s AND `categoria` = %s",varSQL(__sistema),varSQL($_GET['text1'])),true);

	if($c!=false){
		foreach($c['resultado'] as $p){
			$r = __url_real."pg/ver/".$p['id']."/".urlTitulo($p["titulo"])."/";
			$res['cuerpo'][] = array('id'=>$p['id'],'titulo'=>$p['titulo'],'ruta'=>$r);
		}
	}
	return $res;
}