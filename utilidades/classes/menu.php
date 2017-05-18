<?
class menu{
	public static function mostrar($menu,$class='',$id=''){
		$m = menu::construir($menu,$class);
		if(!$m)return false;
		$c = ($class == '')?$c = 'menu_'.$menu:$c = $class;
		$init = '<ul class="'.$c.'"';
		if($id != ''){
			$init .= ' id="'.$id.'"';
		}
		$init .= ">";
		$end = "</ul>";
		echo $init.$m.$end;
	}

	static function construir($menu,$class){
		$construido = '';
		$con = sprintf("SELECT * FROM `menus_items` WHERE `id_menu` = %s ORDER BY `orden` ASC",varSQL($menu));
		$items = consulta($con,true);
		if($items!=false){
			$i = 1;
			foreach($items['resultado'] as $item){
				$construido .= '<li class="'.$class.'-'.$i.'"><a ';
				$construido .= 'href="';
				$construido .= ($item['url']=='')?'#':$item['url'];
				$construido .= '" ';
				$construido .= ($item['target']==0)?'':' target="_blank"';
				$construido .= '>';
				$construido .= $item['titulo'];
				$construido .= '</a>';
				$construido .= '</li>';
				$i++;
			}
			return $construido;
		}else{
			return false;
		}
	}


}