<?
class logueado{
	public static function componenteActual(){
		$get    = array('modulo','funcion','text1','text2','text3','text4');
		$actual = array('modulo'=>array(),'indice'=>0);
		$indice = 0;
		foreach($_SESSION['componentes'] as $modulo){
			$aux = 0;
			foreach($get as $i){
				$aux++;
				if(isset($modulo[$i])&&$modulo[$i]!=''){
					$val = (isset($_GET[$i])&&$_GET[$i]==$modulo[$i]&&$aux>$indice)?true:false;
				}else{
					break;
				}
			}
			if($val){
				$indice = $aux;
			    $actual['modulo'] = $modulo;
			    $actual['indice'] = $indice;
			}
		}
		return $actual;
	}

	public static function permitirAcceso(){
		$actual = logueado::componenteActual();
		//los administradores tienen acceso a todos los componentes instalados
		if(($_SESSION['usuario_perfil']['p']==1&&$actual['indice']!=0)||($_SESSION['SITE_CNFG']['rol']==1&&$_SESSION['usuario_perfil']['p']==1)){
			return true;
		}
		if($actual['indice']!=0){
			foreach($_SESSION['usuario_componentes'] as $componente){
				if($componente['id']==$actual['modulo']['id']){
					return true; break;
				}
			}
		}
		return false;
	}

}
