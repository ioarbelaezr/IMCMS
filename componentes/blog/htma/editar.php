<link rel="stylesheet" href="/frontend/jcrop/jcrop-css.css">
<script inmovil type="text/javascript" src="/frontend/jcrop/jcrop.js"></script>
<script inmovil>
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
	        $(image).css('width', '100%');
	        $(image).css('max-width', '500px');
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
	  

	  $(document).ready(function(){
	    $("#imgInp").change(function(){
	        readURL(this);
	    });
	  })
 
</script>
<?
$sql = sprintf("SELECT * FROM `blog_articulos` WHERE `id` = %s LIMIT 1",$_GET['text1']);
$p = consulta($sql,true);
$p = $p['resultado'][0];
?>


<div class="ancho_base">
	<form method="post" action="/xadm/blog/saveEdt/" id="save_blog" accept-charset="UTF-8">
		<input type="hidden" name="key" value="<?=base64_encode($_GET['text1']); ?>">
	  <div class="form-group">
	    <label for="exampleInputEmail1">Titulo</label>
	    <input name="titulo" type="text" class="form-control" value="<?=$p['titulo']; ?>" id="exampleInputEmail1" placeholder="Titulo">
	  </div>
	  <div class="form-group">
	    <label for="exampleInputEmail1">Subtitulo</label>
	    <input name="subtitulo" type="text" class="form-control" value="<?=$p['subtitulo']; ?>" id="exampleInputEmail1" placeholder="Titulo">
	  </div>
	  <div class="form-group">
	    <label for="exampleInputEmail1">Imagen de caratula</label>
	    <input type='file' id="imgInp" name="OG" />
      	<img id="blah" class="crop" src="" alt="your image" />
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
	  <div class="form-group">
	    <label for="exampleInputPassword1">Contenidos</label>
	    <textarea id="editor" name="contenido" style="width:100%">
            <?=$p['contenido'];?>
        </textarea>
		<? editor('#editor'); ?>
	  </div>
	  <button type="submit" class="btn btn-default">Guardar</button>
	</form>

</div>


<script>
$(document).ready(function (e) {
    $('#save_blog').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                alert(data);
            },
            error: function(data){
                alert("Ha ocurrido un error inesperado en el sistema");
            }
        });
    }));
});
</script>
