<?
function pg(){
	echo "index de un modulo global";
	dump($_GET);
}

function ver(){
	$p = consulta(sprintf("SELECT `titulo`,`contenido` FROM `contenidos` WHERE `id` = %s ",varSQL($_GET['text1'])),true);
	tituloHTML($p["resultado"][0]["titulo"]);
	
	echo '<div class="modulos-content-box">'.$p["resultado"][0]["contenido"].'</div>';
}