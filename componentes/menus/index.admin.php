<?

function menus(){
	require('htma/menus.php');
}

function add(){
	$sql = sprintf("INSERT INTO `menus` (`nombre`,`cloud`) VALUES (%s,%s)",
		varSQL($_POST['titulo']),
		varSQL(__sistema));
	$res = consulta($sql);
	header('Location: '.__url_real.'adm/menus/edt/'.$res['IDI'].'/');
}
function edt(){
	require('htma/editar.php');
}

//funciones ajax

function delMenu(){
	$c = sprintf("DELETE FROM `menus` WHERE `id` = %s",varSQL($_POST['id']));
	consulta($c);
}

function addItem(){
	$orden = consulta(sprintf("SELECT `orden` FROM `menus_items` WHERE `id_menu` = %s AND `id_padre` = %s ORDER BY `orden` DESC LIMIT 1",varSQL($_POST['menu']),varSQL($_POST['padre'])),true);
	if($orden==false){
		$norden = 1;
	}else{
		$norden = $orden['resultado'][0]['orden']+1;
	}
	$consulta = sprintf("INSERT INTO `menus_items` (`id`,`id_menu`,`id_padre`,`titulo`,`url`,`target`,`orden`) VALUES(%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `titulo` = %s,`url` = %s, `target` = %s",varSQL($_POST['edt']),varSQL($_POST['menu']),varSQL($_POST['padre']),varSQL($_POST['titulo']),varSQL($_POST['url']),varSQL($_POST['target']),varSQL($norden),varSQL($_POST['titulo']),varSQL($_POST['url']),varSQL($_POST['target']));
	$res = consulta($consulta);
	echo json_encode($res);
}

function delItem(){
	$consulta = sprintf("DELETE FROM `menus_items` WHERE `id` = %s",varSQL($_POST['id']));
	consulta($consulta);
}

function reOrdenar(){
	$o = json_decode($_POST['m'],true);
	$j = 1;
	foreach($o as $i){
		$u = sprintf('UPDATE `menus_items` SET `orden` = %s WHERE `id` = %s',varSQL($j),varSQL($i['id']));
		consulta($u);
		$j++;
	}
}

