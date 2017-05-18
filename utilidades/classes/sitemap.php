<?
	class sitemap{
		var $header;
		var $footer;
		

		function __construct(){
			$this->header = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
			';
			$this->footer = '
</urlset>';
			$_SESSION['url_set'] = '';
		}
		//convierte de timestamp a fecha en formato w3c
		public function wdate($time='') {
		    if (empty($time)){$time = time();}
		    if (is_string($time)){$time = strtotime($time);}
		    $offset = date("O",$time);
		    return date("Y-m-d\TH:i:s",$time).substr($offset,0,3).":".substr($offset,-2);
		}

		//obtiene la lista de modulos istalados
		function modular(){
			$mods = array();
			$modulos_genericos = consulta(sprintf("SELECT * FROM `componentes_instalados` WHERE `cloud` = %s",varSQL(__sistema)),true);
			$mods = ($modulos_genericos==false)?false:$modulos_genericos['resultado'];
			return $mods;
		}

		function componer(){
			$m = sitemap::modular();
			//generar las url de los modulos instalados
			if($m!=false){
				foreach($m as $modulo){
					$current = $modulo['carpeta'];
					if(file_exists(__path.'componentes/'.$current.'/sitemap.php')){
						require(__path.'componentes/'.$current.'/sitemap.php');
						continue;
					}
					if(file_exists('componentes/'.$current.'/sitemap.php')){
						require('componentes/'.$current.'/sitemap.php');
						continue;
					}
				}
			}			
		}

		function mostrar(){
			header ("Content-Type:text/xml");
			//agregar el listado de urls declarado en cada sitio si esxiste
			if(file_exists(__path.'sitemap.php')){
				require(__path.'sitemap.php');
			}
			$this->componer();
			sitemap::addUrl('','daily',NULL,'1.00');//mostrar la pagina principal
			echo $this->header.$_SESSION['url_set_add'].$_SESSION['url_set'].$this->footer;
			sitemap::reset();
		}

		public function addUrl($url,$frec="weekly",$lastMod=NULL,$p="0.70"){
			$_SESSION['url_set_add'] = (!isset($_SESSION['url_set_add']))?'':$_SESSION['url_set_add'];
			$url = __url_real.$url;
			$_SESSION['url_set_add'] .= '
			<url>
				<loc>'.utf8_encode($url).'</loc>
				<lastmod>'.sitemap::wdate($lastMod).'</lastmod>
				<changefreq>'.$frec.'</changefreq>
				<priority>'.$p.'</priority>
			</url>';
		}

		public function addFortmat($formatUrl,$pieces,$frec='weekly',$lastMod=NULL,$p='0.7'){
			if(!is_array($pieces))return false;
			$searchs = array();
			$replaces = array();
			foreach ($pieces as $key => $value) {
				$searchs[] = '{{'.$key.'}}';
				$replaces[] = $value;
			}
			$url = $formatUrl;
			$url = str_replace($searchs, $replaces, $url);
			sitemap::addUrl($url,$frec,$lastMod,$p);
		}

		public function reset(){
			$_SESSION['url_set'] = '';
			$_SESSION['url_set_add'] = '';
		}
	}