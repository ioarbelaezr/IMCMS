<?
if(isset($_POST['titulo'])){
	$q = sprintf("UPDATE `contenidos` SET `titulo` = %s, `contenido` = %s WHERE `id` = %s",varSQL($_POST['titulo']),varSQL($_POST['contenido']),varSQL($_GET['text1']));
	consulta($q);
}

$sql = sprintf("SELECT * FROM `contenidos` WHERE `id` = %s LIMIT 1",$_GET['text1']);
$p = consulta($sql,true);
$p = $p['resultado'][0];
?>


<div class="ancho_base">
	
	<form method="post" action="">
	  <div class="form-group">
	   <div class="row">
	   	<div class="col-xs-10">
	   		<label for="titulo">Titulo</label>
	    	<input name="titulo" type="text" class="form-control" value="<?=$p['titulo']; ?>" id="titulo" placeholder="Titulo">
	   	</div>
	   	<div class="col-xs-2 text-center">
	   		<button onclick="modal(<?=$_GET['text1']; ?>);" style="margin-top: 22px;" type="button" class="btn btn-default"><i class="fa fa-cog" aria-hidden="true"></i> &nbsp;Opciones</button>	
	   	</div>
	   </div>
	  </div>
	  <div class="form-group">
	    <textarea id="editor" name="contenido" style="width:100%">
            <?=$p['contenido'];?>
        </textarea>
		<? editor('#editor'); ?>
	  </div>
	  <div class="text-center">
	  	<button type="submit" class="btn btn-default">Guardar cambios</button>
	  </div>
	</form>
</div>

<div class="modal fade" id="opciones" tabindex="-1" role="dialog" aria-labelledby="opcionesPagina">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>

<script>
	function modal(id){
		$('#opciones').modal().find('.modal-body').load("/xadm/pg/opciones/"+id+"/");
	}
</script>