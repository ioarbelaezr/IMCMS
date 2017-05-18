<? 
	if($_SERVER['REQUEST_METHOD']=='POST'){
		$sql = sprintf("UPDATE `clouds` SET `titulo` = %s, `descripcion` = %s, `palabras` = %s WHERE `dominio` = %s",varSQL($_POST['titulo']),varSQL($_POST['descripcion']),varSQL($_POST['palabras']),varSQL(str_replace('www.', '', $_SERVER['HTTP_HOST'])));
		consulta($sql);
		if(isset($_FILES)){
			//icono del sitio
			if($_FILES['ico']['name']!=''){
				$destino = $_SERVER["DOCUMENT_ROOT"].__path.'recursos/icons/favicon.ico';
				@move_uploaded_file($_FILES['ico']['tmp_name'],$destino);
				@chmod($destino, 0755);
			}
			//og del sitio
			if($_FILES['og']['name']!=''){
				$destino = $_SERVER["DOCUMENT_ROOT"].__path.'recursos/img/og.jpg';
				@move_uploaded_file($_FILES['og']['tmp_name'],$destino);
				@chmod($destino, 0755);
			}
		}
		header('Location: /adm/config/siteConfig/');
	}
 ?>

<form action="" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-xs-12">
			<b style="font-size:32px;">Configuración del sitio&nbsp;&nbsp; (<?=__dominio; ?>)</b> 
		</div>
	</div>


	<div class="row">
		<div class="col-xs-12">
			<h3><i class="fa fa-flag" aria-hidden="true"></i>&nbsp; &nbsp;Título del sitio web.</h3>
		</div>
		<div class="col-xs-12">
			<p>
				El título que contienen las páginas Web de nuestro sitio es el punto más importante que debemos considerar si se quiere que nuestro sitio Web este bien posicionado en los motores de búsqueda de Internet. Algunos tips para un buen título:
			</p>
			<p>
				<ul>
					<li>Rico en palabras clave (keywords). Por lo menos debería contener la palabra clave con la cual deseamos estar bien posicionados.</li>
					<li>No muy largo. No debería ser mayor de 95 caracteres, porque a partir del carácter 96 el texto no es visible en la barra superior del navegador y aunque el buscador puede leerlo, nuestros visitantes verán el título incompleto y causaremos una mala sensación. Un título con 50-75 caracteres podría ser un tamaño óptimo.</li>
					<li>Suficientemente descriptivo del contenido de la página. El título es la frase con la cual describimos en pocas palabras de lo que trata una página web. </li>
				</ul>
			</p>
		</div>
		<div class="col-xs-12">
			<div class="form-group">
			    <input name="titulo" value="<?=$_SESSION['SITE_CNFG']['titulo']; ?>" type="text" class="form-control" id="exampleInputEmail1" placeholder="Título">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<h3><i class="fa fa-align-right" aria-hidden="true"></i>&nbsp; &nbsp;Descripción del sitio web.</h3>
		</div>
		<div class="col-xs-12">
			<p>
				La meta descripción o meta description tag, es una etiqueta HTML que sirve para porporcionar una descripción del contenido de la página a los buscadores. Algunos tips para un buen título:
			</p>
		</div>
		<div class="col-xs-12">
			<div class="form-group">
			    <input name="descripcion" value="<?=$_SESSION['SITE_CNFG']['descripcion']; ?>" type="text" class="form-control" id="exampleInputEmail1" placeholder="Descripción">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<h3><i class="fa fa-align-right" aria-hidden="true"></i>&nbsp; &nbsp;Palabras clave.</h3>
		</div>
		<div class="col-xs-12">
			<p>
				Las palabras clave son mucho menos importantes que el Título y Descripción. La mayoria de buscadores simplemente las ignora.
			</p>
			<p>
				Ingrese tantas palabras o frases claves como pueda, separadas por una coma ",". Trata que describan de la mejor manera tu web
			</p>
		</div>
		<div class="col-xs-12">
			<div class="form-group">
			    <textarea name="palabras" class="form-control" rows="10"><?=$_SESSION['SITE_CNFG']['palabras']; ?></textarea>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-6">
			<div>
				<h3><i class="fa fa-file-image-o" aria-hidden="true"></i>&nbsp;&nbsp;Imagen por defecto del sitio</h3>
			</div>
			<div>
				<input onchange="extension(this,'.jpg','#msj_og')" name="og" class="form-control" type="file">
			</div>
			<div id="msj_og"><b style="color:red;">Tipo de archivo no valido</b></div>
			<div>
				<p>Es la imagen por defecto mostrada cuando no se encuentra un recurso de imagen, tambien para compartir el sitio web en redes sociales. Debe ser una imagen <b>.PNG</b> de al menos 700x700 px.</p>
			</div>
		</div>
		<div class="col-xs-6">
			<div>
				<h3><i class="fa fa-bell-o" aria-hidden="true"></i>&nbsp;&nbsp;Favicon del sitio</h3>
			</div>
			<div><input onchange="extension(this,'.ico','#msj_ico')" name="ico" class="form-control" type="file"></div>
			<div id="msj_ico"><b style="color:red;">Tipo de archivo no valido</b></div>
			<div>
				<p>Es el icono asociado a la web que sirve para identificarla visualmente en la barra de favoritos, y otros emplazamientos variables (dependiendo del navegador). Debe ser in archivo tipo <b>.ICO</b></p>
			</div>
		</div>
	</div>

	<div class="col-xs-12 text-center">
		<button type="submit" class="btn btn-default">Guardar</button>
		<br><br><br>
	</div>
</form>
<style>
	#msj_og, #msj_ico{display: none;}
</style>
<script>
	function extension(a,b,c) {
		var d = (a.value.substring(a.value.lastIndexOf("."))).toLowerCase(); 
		if(d!=b){
			$(c).show();
			a.value = '';
			a.focus();
		}else{
			$(c).hide();
		}
	}
</script>