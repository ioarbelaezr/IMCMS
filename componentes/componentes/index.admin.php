<?
function componentes(){
	require('htma/componentes.php');
}

function componentes_instalar(){
	require("htma/instalador.php");
}

function crearComponente(){
	$desde = __root."componentes/componentes/componentes_template/";
	$hasta = __root."componentes/".$_POST['carpeta']."/";
	$sql = sprintf("INSERT INTO `componentes_disponibles` (`componente`,`carpeta`,`modulo`,`funcion`,`text1`,`text2`,`text3`,`text4`) VALUES(%s,%s,%s,%s,%s,%s,%s,%s)",varSQL($_POST['nombre']),varSQL($_POST['carpeta']),varSQL($_POST['carpeta']),varSQL($_POST['funcion']),varSQL($_POST['t1']),varSQL($_POST['t2']),varSQL($_POST['t3']),varSQL($_POST['t4']));
	consulta($sql);	
	if(!file_exists($hasta)){
		copiar($desde,$hasta);
		$index = fopen($hasta.'index.php', "w+");
		$c     = "<?".PHP_EOL; 
		$c    .= "function ".$_POST['carpeta']."(){".PHP_EOL;
		$c    .= "    echo \"estoy ubicado en: componentes/".$_POST['carpeta']."/index.php\";".PHP_EOL;
		$c    .= "}"; 
		fwrite($index,$c);
		fclose($index);
	}
	
}

/**/
function instalar(){
	$m   = consulta(sprintf("SELECT * FROM `componentes_disponibles` WHERE `id` = %s",varSQL($_POST['id'])),true);
	if($m!=false){
		$m = $m['resultado'][0];
		$sql = sprintf("INSERT INTO `componentes_instalados` (`cloud`,`id_componente`,`nombre`,`carpeta`,`modulo`,`funcion`,`text1`,`text2`,`text3`,`text4`) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",varSQL($_POST['cloud']),varSQL($m['id']),varSQL($m['componente']),varSQL($m['carpeta']),varSQL($m['modulo']),varSQL($m['funcion']),varSQL($m['text1']),varSQL($m['text2']),varSQL($m['text3']),varSQL($m['text4']));
		$r = consulta($sql);
		echo json_encode(array('id'=>$r['IDI']));
	}else{
		echo json_encode(array('id'=>0));
	}
}

function desinstalar(){
	consulta(sprintf("DELETE FROM `componentes_instalados` WHERE `id` = %s",varSQL($_POST['id'])));
	echo json_encode(array('s'=>1));
}