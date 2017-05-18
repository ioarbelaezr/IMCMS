<?
	$articulo = consulta(sprintf("SELECT * FROM `blog_articulos` WHERE `id` = %s LIMIT 1",varSQL($_GET["text1"])),true);
	$articulo = $articulo['resultado'][0];
	tituloHTML($articulo["titulo"]);
	descripcionHTML($articulo["subtitulo"]);

	if($articulo['og']!=''){
		if(file_exists(__upload_dir.'blog/ogs/'.$articulo['id'].$articulo['og'])){
			//dump($articulo['id'].$articulo['og']);
			$res = __url_real.__path.'uploads/blog/ogs/'.$articulo['id'].$articulo['og'];
			ogImage($res);
		}
	}
?>
<div class="blog_articulo_contenedor">
	<div class="row">
		<div class="col-xs-12">
			<?=$articulo["contenido"]; ?>
		</div>
	</div>
</div>

<style type="text/css">
	.blog_articulo_contenedor{width: 100%;margin: auto;max-width: 990px;}
</style>