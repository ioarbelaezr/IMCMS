<?
if(!isset($_GET['accion'])||$_GET['accion']==''){
	echo json_encode(array("estado"=>"fallo","code"=>1));
}else{
	if(file_exists('acciones.php')){
		require('acciones.php');
		if(function_exists($_GET['accion'])){
			$_GET['accion']();
		}else{
			echo json_encode(array("estado"=>"fallo","code"=>3));
		}
	}else{
		echo json_encode(array("estado"=>"fallo","code"=>2));
	}
}