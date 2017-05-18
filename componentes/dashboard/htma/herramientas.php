<ul class="herramientas">
	<?
		$componentes = ($_SESSION['usuario_perfil']['p']==1)?$_SESSION['componentes']:$_SESSION['usuario_componentes'];

		foreach($componentes as $componente){
			$url = '/adm/';
			$url.= ($componente['modulo']!='')?$componente['modulo'].'/':'';
			$url.= ($componente['funcion']!=''&&$componente['modulo']!=$componente['funcion'])?$componente['funcion'].'/':'';
			$url.= ($componente['text1']!='')?$componente['text1'].'/':'';
			$url.= ($componente['text2']!='')?$componente['text2'].'/':'';
			$url.= ($componente['text3']!='')?$componente['text3'].'/':'';
			$url.= ($componente['text4']!='')?$componente['text4'].'/':'';
			$ico = '/componentes/dashboard/res/app.png';
			if(file_exists(__root.'componentes/'.$componente['carpeta'].'/res/icons/icon.png')){
				$ico = '/componentes/'.$componente['carpeta'].'/res/icons/icon.png';	
			}
			?>
			<a href="<?=$url;?>">
				<li>
					<img src="<?=$ico; ?>">
					<div class="herramienta"><?=$componente['nombre']; ?></div>
				</li>
			</a>
		<?}
	?>
</ul>
<style type="text/css">
	.herramientas{list-style-type: none;padding: 0;margin: 0;}
	.herramientas li{width: 120px;height: 180px;display: block;text-align:center;cursor: pointer;border:1px solid transparent;float:left;}
	.herramientas li:hover{background-color: rgba(255,255,255,.3);border: 1px solid #d6e9f3;border-radius: 3px;}
	.herramientas li img{margin-top:10px;}
	.herramientas a{color:black !important;text-decoration: none;}
	.herramienta{margin-top:15px;font-size: 17px;}
</style>

