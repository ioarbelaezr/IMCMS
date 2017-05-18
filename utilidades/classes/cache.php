<?

class cache{
	public function cachable(){
		//modulos a excluir 
		$modulos = array('login');
		//funciones en los modulos a excluir
		$funciones = array();
		return (!inicie_sesion()&&(!in_array($_GET['modulo'], $modulos)&&!in_array($_GET['funcion'], $funciones))&&!isset($_GET['ajax']));
	}

	public function memcache(){
		//verificar si se activa la cache del sistema
		if(!cache::cachable())return false;
		$mem = new Memcached;
		if(!@$mem->addServer("localhost", 11211))return false;
		$data = $mem->get(__url_canonica);
		$mem->quit();
		if($data==false||$data==''){
		    return false;
		}else{

        	@ob_end_clean();
        	ini_set('zlib.output_compression_level', 7);
        	ob_start();
        	echo utf8_encode($data);
        	ob_flush();
        	exit();
		}
	}

	public function set($contenido='',$tiempo=60){
		if(!cache::cachable())return false;
		$mem = new Memcached;
		if(!@$mem->addServer("localhost", 11211))return false;
		$mem->set(__url_canonica,$contenido,$tiempo);
		$mem->quit();
	}
}