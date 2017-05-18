<?
	if(isset($_GET['text1'])){
		$sql = sprintf("SELECT * FROM `login` WHERE `cloud` = %s AND `id` = %s LIMIT 1",
        varSQL(__sistema),
        varSQL($_GET['text1']));
    	$datos = consulta($sql,true);
    	$datos_usuario = $datos['resultado'][0];
	}else{
		$datos_usuario = $_SESSION['data_login'];
	}
?>

<link rel="stylesheet" href="/frontend/jcrop/jcrop-css.css">
<script inmovil type="text/javascript" src="/frontend/jcrop/jcrop.js"></script>
<script>
	var jcrop_api;
	function readURL(input) {
	  if (input.files && input.files[0]) {
	    var reader = new FileReader();
	    reader.onload = function (e) {
	      
	      var image = document.getElementById('blah');
	      image.src = e.target.result;
	      image.onload = function(){
	        if (typeof jcrop_api != 'undefined') {
	            jcrop_api.destroy();
	            jcrop_api = null;
	        };
	        $('#Nw').val(image.naturalWidth);
	        $('#Nh').val(image.naturalHeight);
	        $('#Rw').val($(image).width());
	        $('#Rh').val($(image).height());
	        $('.crop').Jcrop({
	          onSelect: updateCoords,
	          bgOpacity:.4,
	          aspectRatio: 1/1
	       },function(){
	          jcrop_api = this;
	        });
	      }
	    }
	    reader.readAsDataURL(input.files[0]);
	  }
	};
	    
	    
	  function updateCoords(c){
	    console.log(c);
	    $('#x1').val(c.x);
	    $('#y1').val(c.y);
	    $('#x2').val(c.x2);
	    $('#y2').val(c.y2);
	    $('#w').val(c.w);
	    $('#h').val(c.h);
	  };
	  
	  $(document).ready(function(){$("#imgInp").change(function(){var a=this;return a.files[0].size>2097152?(alert("Solo se permiten archivos menores a 2 MB"),!1):($("#modalPerfil").modal("show"),void $("#modalPerfil").on("shown.bs.modal",function(b){readURL(a)}))})});
</script>

<script>
	function guardarImagen(){
		if($('#w').val()==''||$('#h').val()==''){
			alert('Slecciona la area de la imagen a guardar');
		}else{
			$('#imagenPerfil').submit();
		}	
	}

	function eliminarImagen(){
		var data = $.para({user:"<?=base64_encode($datos_usuario['id']); ?>"});
		$.post('/xadm/admUsuarios/eliminarImagenPerfil/',data,function(){
			$('.imagenCapa img<?=(isset($_GET['text1'])?'':', .user_image img'); ?>').remove();
		});
	}

	$(document).ready(function (e) {
	    $('#imagenPerfil').on('submit',(function(e) {
	        e.preventDefault();
	        var formData = new FormData(this);
	        $('.imagenCapa span').show();
	        $("#modalPerfil").modal("hide");
	        $.ajax({
	            type:'POST',
	            url: $(this).attr('action'),
	            data:formData,
	            cache:false,
	            contentType: false,
	            processData: false,
	            dataType : 'json',
	            success:function(data){
	                $('.imagenCapa').append();
	                var img = $('<img>');
					img.attr('src', '/imagenesDePerfil/'+data.imagen);
					
					$.when($('.imagenCapa img<?=(isset($_GET['text1'])?'':', .user_image img'); ?>').remove()).then(img.appendTo('.imagenCapa<?=(isset($_GET['text1'])?'':', .user_image'); ?>'));
	                $('.imagenCapa span').hide();
	            },
	            error: function(data){
	                alert("Ha ocurrido un error inesperado en el sistema");
	            }
	        });
	    }));

	    $('#formDatos').on('submit',function(e){
	    	e.preventDefault();
	    	var data = $.param({nombres:$('#nombres').val(),apellidos:$('#apellidos').val(),email:$('#email').val(),telefono:$('#telefono').val(),user:'<?=base64_encode($datos_usuario['id']); ?>'});
	    	$.post('/xadm/admUsuarios/actualizarDatos/',data,function(res){
	    		$('#datosMensaje').show();
	    	});
	    });

	    $('#formContrasena').on('submit',function(e){
	    	e.preventDefault();
	    	var data = $.param({contrasena:$('#contrasena').val(),nContrasena:$('#nContrasena').val(),user:'<?=base64_encode($datos_usuario['id']); ?>'});
	    	$.post('/xadm/admUsuarios/actualizarContrasena/',data,function(res){
	    		$('#cMensaje').show().append(res);
	    	});
	    });
	});
</script>
<div class="" style="position:relative;">
	<div class="row">
		<div class="col-xs-4">
			<form accept-charset="UTF-8" action="/xadm/admUsuarios/guardarImagenPerfil/" method="post" id="imagenPerfil" accept-charset="UTF-8">
				<div style="margin:auto;width:200px;" class="form-group">
					<label for="exampleInputEmail1">Imagen de perfil</label>
					<div class="imagenCapa" id="imagenCapa">
						<span>
							<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						</span>
						<?
							if($datos_usuario['imagen']!=''){?>
								<img src="/imagenesDePerfil/<?=$datos_usuario['imagen']; ?>"/>
							<?}
						?>
					</div>
					<div class="botonAccion">
						<label for="imgInp" id="Selector" class="Selector">
							<i class="fa fa-upload" aria-hidden="true"></i> 
						</label>
						<label onclick="eliminarImagen();" style="margin-left: 10px;" id="Selector" class="Selector">
							<i class="fa fa-trash" aria-hidden="true"></i> 
						</label>
						<input style="display:none;" type='file' id="imgInp" name="perfil" accept="image/jpeg, image/png, image/jpg"/>
					</div>
					<input type="hidden" name="user" value="<?=base64_encode($datos_usuario['id']); ?>">
					<input type="hidden" id="x1" name="x1" />
					<input type="hidden" id="y1" name="y1" />
					<input type="hidden" id="x2" name="x2" />
					<input type="hidden" id="y2" name="y2" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />
					<input type="hidden" id="Nw" name="Nw" />
					<input type="hidden" id="Nh" name="Nh" />
					<input type="hidden" id="Rw" name="Rw" />
					<input type="hidden" id="Rh" name="Rh" />
				</div>
			</form>
		</div>
		<div class="col-xs-5">
			<form id="formDatos">
				<input type="hidden" name="user" value="<?=base64_encode($datos_usuario['id']); ?>">
				<div class="form-group">
					<label for="exampleInputEmail1">Nombres</label>
					<input value="<?=$datos_usuario['nombres']; ?>" required type="text" class="form-control" id="nombres" placeholder="Nombres" name="nombres">
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Apellidos</label>
					<input value="<?=$datos_usuario['apellidos']; ?>" type="text" class="form-control" id="apellidos" placeholder="Apellidos" name="apellidos">
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Correo electrónico</label>
					<input  value="<?=$datos_usuario['email']; ?>" required type="text" class="form-control" id="email" placeholder="Correo electrónico" name="email">
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Teléfono</label>
					<input  value="<?=$datos_usuario['telefono']; ?>" type="text" class="form-control" id="telefono" placeholder="Teléfono" name="telefono">
				</div>
				<div style="display:none;" id="datosMensaje" class="text-center">
					<span style="color:green;">Guardado!</span>
				</div>
				<button type="submit" class="btn btn-default">Guardar cambios</button>
			</form>
			<br>	<br>
			<form id="formContrasena">
				<label for="">Actualizar contraseña</label>
				<div class="form-group">
					<label class="sr-only" for="exampleInputEmail3">Contraseña</label>
					<input <?=(isset($_GET['text1'])?'"disabled"':''); ?> id="contrasena" type="password" class="form-control" id="exampleInputEmail3" placeholder="contraseña actual">
				</div>
				<div class="form-group">
					<label class="sr-only" for="exampleInputPassword3">Nueva cobtraseña</label>
					<input required id="nContrasena" type="password" class="form-control" id="exampleInputPassword3" placeholder="Nueva contraseña">
				</div>
				<div class="text-center" style="display:none;" id="cMensaje">
					
				</div>
				<button type="submit" class="btn btn-default">Actualizar contraseña</button>
			</form>
		</div>
	</div>
</div>

<div id="modalPerfil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel">Seleccionar imagen de perfil</h4>
      </div>
      <div class="modal-body">
        <img style="width:870;max-width:870px;" id="blah" class="crop" src="" alt="Imagen de perfil" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button onclick="guardarImagen();" type="button" class="btn btn-primary">Guardar cambios</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
	.imagenCapa{width: 200px;height: 200px;background-color: green;position: relative;}
	.imagenCapa span{display: none;height: 50px;width: 50px;position: absolute;left: 50%;top: 50%;transform: translateX(-50%) translateY(-50%);text-align: center;line-height: 50px;color: #E2EEF4;}
	.imagenCapa img{width: 100%;}
	.botonAccion{margin-top: 10px;}
	.Selector{width: 30px;height: 30px;cursor: pointer;background: #272634;color: white;text-align: center;font-size: 20px;line-height: 30px;}
	.jcrop-keymgr{display: none;}
	.modal-content{overflow: hidden;}
</style>